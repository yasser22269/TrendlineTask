<?php
namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function all()
    {
        return Payment::paginate(PAGINATION_COUNT);
    }

    public function show($id)
    {
        return Payment::findOrFail($id);
    }

    public function store(array $data)
    {
        return Payment::create($data);
    }

    public function update($id, array $data)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($data);
        return $payment;
    }

    public function destroy($id)
    {
        return Payment::destroy($id);
    }
}
