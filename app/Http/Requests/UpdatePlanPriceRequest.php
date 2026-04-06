<?php

namespace App\Http\Requests;

use App\Enums\BillingCycle;
use App\Enums\Currency;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanPriceRequest extends FormRequest
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
            'plan_id' => 'sometimes|exists:plans,id',
            'price' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|in:'.implode(',', Currency::values()),
            'billing_cycle' => 'sometimes|in:'.implode(',', BillingCycle::values()),
        ];
    }
}
