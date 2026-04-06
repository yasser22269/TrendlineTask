<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanPriceRequest;
use App\Http\Requests\UpdatePlanPriceRequest;
use App\Http\Resources\PlanPriceResource;
use App\Models\PlanPrice;
use App\Repositories\Contracts\PlanPriceRepositoryInterface;

class PlanPriceController extends Controller
{
    protected $planPrices;

    public function __construct(PlanPriceRepositoryInterface $planPrices)
    {
        $this->planPrices = $planPrices;
    }

    public function index()
    {
        return successResponse(PlanPriceResource::collection($this->planPrices->all()));
    }

    public function show($id)
    {
        return successResponse(new PlanPriceResource($this->planPrices->show($id)));
    }

    public function store(StorePlanPriceRequest $request)
    {
        $data = $request->validated();
        $planPrice = $this->planPrices->store($data);
        return successResponse(new PlanPriceResource($planPrice));
    }

    public function update(UpdatePlanPriceRequest $request, $id)
    {
        $data = $request->validated();
        $planPrice = $this->planPrices->update($id, $data);
        return successResponse(new PlanPriceResource($planPrice));
    }

    public function destroy($id)
    {
        $this->planPrices->destroy($id);
        return successResponse(null, 'Plan price deleted successfully');
    }
}
