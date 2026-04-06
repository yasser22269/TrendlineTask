<?php
namespace App\Repositories\Eloquent;

use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function all()
    {
        return Subscription::with(['plan', 'payments'])->paginate(PAGINATION_COUNT);
    }

    public function store(array $data)
    {
        return Subscription::create($data);
    }

    public function show($id)
    {
        return Subscription::with(['plan', 'payments'])->findOrFail($id);
    }

    public function userSubscriptions($userId)
    {
        return Subscription::where('user_id', $userId)->with(['plan', 'payments'])->paginate(PAGINATION_COUNT);
    }

    public function update($id, array $data)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update($data);
        return $subscription->load(['plan', 'payments']);
    }

    public function destroy($id)
    {
        return Subscription::destroy($id);
    }
}
