<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    protected $plans;

    public function __construct(PlanRepositoryInterface $plans)
    {
        $this->plans = $plans;
    }

    public function index()
    {
        return successResponse(PlanResource::collection($this->plans->all()));
    }

    public function show($id)
    {
        return successResponse(new PlanResource($this->plans->show($id)));
    }

    public function store(StorePlanRequest $request)
    {
         try {
            DB::beginTransaction();
        $data = $request->validated();
        $prices = $data['prices'] ?? [];
        unset($data['prices']);

        $plan = $this->plans->store($data);

        if (!empty($prices)) {
            foreach ($prices as $price) {
                $plan->prices()->create($price);
            }
        }
                  DB::commit();

        return successResponse(new PlanResource($plan->load('prices')));

        } catch (\Exception $e) {
            DB::rollBack();
            return failureResponse($e->getMessage());
        }
    }

    public function update(UpdatePlanRequest $request, $id)
    {
        $data = $request->validated();
        $prices = $data['prices'] ?? [];
        unset($data['prices']);

        $plan = $this->plans->update($id, $data);

        if (!empty($prices)) {
            foreach ($prices as $price) {
                if (isset($price['id'])) {
                    $plan->prices()->where('id', $price['id'])->update($price);
                } else {
                    $plan->prices()->create($price);
                }
            }
        }

        return successResponse(new PlanResource($plan->load('prices')));
    }

    public function destroy($id)
    {
        $this->plans->destroy($id);
        return successResponse(null, 'Plan deleted successfully');
    }
}
