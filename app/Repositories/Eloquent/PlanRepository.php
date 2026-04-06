<?php
namespace App\Repositories\Eloquent;

use App\Models\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;

class PlanRepository implements PlanRepositoryInterface
{
    public function all()
    {
        return Plan::with(['prices'])->paginate(PAGINATION_COUNT);
    }

    public function show($id)
    {
        return Plan::with(['prices'])->findOrFail($id);
    }

    public function store(array $data)
    {
        return Plan::create($data);
    }

    public function update($id, array $data)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($data);
        return $plan->load('prices');
    }

    public function destroy($id)
    {
        return Plan::destroy($id);
    }
}
