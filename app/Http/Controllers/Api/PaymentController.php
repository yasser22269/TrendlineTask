<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class PaymentController extends Controller
{
    protected $payments;

    public function __construct(PaymentRepositoryInterface $payments)
    {
        $this->payments = $payments;
    }

    public function index()
    {
        return successResponse(PaymentResource::collection($this->payments->all()));
    }

    public function show($id)
    {
        return successResponse(new PaymentResource($this->payments->show($id)));
    }

    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();

        $payment = $this->payments->store([
            ...$data,
            'paid_at' => now()
        ]);
        return successResponse(new PaymentResource($payment));
    }

    public function update(UpdatePaymentRequest $request, $id)
    {
        $data = $request->validated();
        $payment = $this->payments->update($id, $data);
        return successResponse(new PaymentResource($payment));
    }

    public function destroy($id)
    {
        $this->payments->destroy($id);
        return successResponse(null, 'Payment deleted successfully');
    }
}
