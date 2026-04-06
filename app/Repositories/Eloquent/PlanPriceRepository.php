<?php
namespace App\Repositories\Eloquent;

use App\Models\PlanPrice;
use App\Repositories\Contracts\PlanPriceRepositoryInterface;

class PlanPriceRepository implements PlanPriceRepositoryInterface
{
    public function all()
    {
        return PlanPrice::paginate(PAGINATION_COUNT);
    }

    public function show($id)
    {
        return PlanPrice::findOrFail($id);
    }

    public function store(array $data)
    {
        return PlanPrice::create($data);
    }

    public function update($id, array $data)
    {
        $planPrice = PlanPrice::findOrFail($id);
        $planPrice->update($data);
        return $planPrice;
    }

    public function destroy($id)
    {
        return PlanPrice::destroy($id);
    }
}
