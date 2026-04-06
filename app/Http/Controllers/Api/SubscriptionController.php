<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\PlanPriceRepositoryInterface;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Services\SubscriptionLifecycleService;

class SubscriptionController extends Controller
{
    protected $subscriptions;
    protected $plans;
    protected $payments;
    protected $plan_price;
    protected $lifecycleService;

    public function __construct(
        SubscriptionRepositoryInterface $subscriptions,
        PaymentRepositoryInterface $payments,
        PlanRepositoryInterface $plans,
        PlanPriceRepositoryInterface $plan_price,
    )
    {
        $this->subscriptions = $subscriptions;
        $this->plans = $plans;
        $this->plan_price = $plan_price;
        $this->payments = $payments;
    }

    public function index()
    {
        return successResponse(
            SubscriptionResource::collection($this->subscriptions->userSubscriptions(auth('api')->id()))
        );
    }

    public function show($id)
    {
        $subscription = $this->subscriptions->show($id);

        return successResponse(new SubscriptionResource($subscription)
        );
    }

    public function subscribe(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();
        $plan = $this->plans->show($data['plan_id']);
        $plan_price = $this->plan_price->show($data['plan_price_id']);

        $subscription = $this->subscriptions->store([
            'user_id' => auth('api')->id(),
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::TRIALING,
            'started_at' => now(),
            'trial_ends_at' => now()->addDays($plan->trial_days ?? 0),
        ]);

        $this->payments->store([
            'amount' => $plan_price->price,
            'currency' => $plan_price->currency,
            'subscription_id' => $subscription->id,
            'status' => PaymentStatus::PENDING
        ]);

        return successResponse(
            new SubscriptionResource($subscription->load(['plan', 'payments'])),
            'Subscription created successfully'
        );
    }



    public function update(UpdateSubscriptionRequest $request, $id)
    {
        $subscription = $this->subscriptions->show($id);
        $data = $request->validated();

        if (isset($data['status'])) {
            $newStatus = $data['status'];

            switch ($newStatus) {
                case SubscriptionStatus::ACTIVE:
                    $subscription = $this->transitionToActive($subscription);
                    break;
                case SubscriptionStatus::PAST_DUE:
                    $subscription = $this->transitionToPastDue($subscription);
                    break;
                case SubscriptionStatus::CANCELED:
                    $subscription = $this->cancelSubscription($subscription);
                    break;
            }
        } else {
            $subscription = $this->subscriptions->update($id, $data);
        }

        return successResponse( new SubscriptionResource($subscription));
    }

    public function destroy($id)
    {
        $subscription = $this->subscriptions->show($id);
        $this->lifecycleService->cancelSubscription($subscription);
        return successResponse();
    }



    public function transitionToPastDue(Subscription $subscription): Subscription
    {
        $oldStatus = $subscription->status;
        if(SubscriptionStatus::PAST_DUE == $oldStatus) {
            return $subscription;
        }
        $subscription->update([
            'status' => SubscriptionStatus::PAST_DUE,
            'grace_period_ends_at' => now()->addDays(GRACE_PERIOD_DAYS),
        ]);

        return $subscription;
    }


    public function transitionToActive(Subscription $subscription): Subscription
    {
        $oldStatus = $subscription->status;
        if(SubscriptionStatus::ACTIVE == $oldStatus) {
            return $subscription;
        }

        $subscription->update([
            'status' => SubscriptionStatus::ACTIVE,
        ]);

        return $subscription;
    }

    public function cancelSubscription(Subscription $subscription): Subscription {
        if ($subscription->status === SubscriptionStatus::CANCELED) {
            return $subscription;
        }

        $subscription->update([
            'status' => SubscriptionStatus::CANCELED,
            'canceled_at' => now(),
        ]);

        return $subscription;
    }

}
