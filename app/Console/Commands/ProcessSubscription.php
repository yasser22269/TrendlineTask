<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;

use Illuminate\Console\Command;

class ProcessSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:process-lifecycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Subscriptions that are trialing and trial has ended - check if they had a successful payment,
            // if so move to active
            Subscription::query()
                ->where('status', SubscriptionStatus::TRIALING )
                ->where('trial_ends_at', '<=', now())
                ->whereHas('payments', function ($query) {
                    $query->where('status', PaymentStatus::SUCCESS);
                })
                ->update([
                    'status' => SubscriptionStatus::ACTIVE,
                ]);

            // Subscriptions that are trialing and trial has ended - check if they had a failed payment,
            // if so move to active
            Subscription::query()
                ->where('status', SubscriptionStatus::PAST_DUE )
                ->where('grace_period_ends_at', '<=', now())
                ->where('trial_ends_at', '<=', now())
                ->whereHas('payments', function ($query) {
                    $query->where('status', PaymentStatus::SUCCESS);
                })
                ->update([
                    'status' => SubscriptionStatus::ACTIVE,
                ]);

            // Subscriptions that are past due and grace period has ended - check if they had a failed payment,
            // if so move to canceled
            Subscription::query()
                ->where('status', SubscriptionStatus::PAST_DUE )
                ->where('grace_period_ends_at', '<=', now())
                ->whereHas('payments', function ($query) {
                    $query->where('status', PaymentStatus::FAILED);
                })
                ->update([
                    'status' => SubscriptionStatus::CANCELED,
                    'canceled_at' => now(),
                ]);


            return true;

        } catch (\Exception $e) {
            $this->error("Error processing subscription lifecycle: {$e->getMessage()}");
            \Log::error('Subscription Lifecycle Error', [
                'exception' => $e,
            ]);
            return false;
        }
    }
}
