<?php

namespace App\Http\Requests;

use App\Enums\Currency;
use App\Enums\PaymentStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
            'subscription_id' => 'sometimes|exists:subscriptions,id',
            'amount' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|in:'.implode(',', Currency::values()),
            'status' => 'sometimes|in:'.implode(',', PaymentStatus::values()),
        ];
    }
}
