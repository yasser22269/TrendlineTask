<?php

namespace App\Http\Requests;

use App\Enums\Currency;
use App\Enums\PaymentStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subscription_id' => 'required|exists:subscriptions,id',
            'amount' => 'required|numeric',
            'currency' => 'required|in:'.implode(',', Currency::values()),
            'status' => 'required|in:'.implode(',', PaymentStatus::values()),
        ];
    }
}
