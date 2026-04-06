<?php

namespace App\Http\Requests;

use App\Enums\BillingCycle;
use App\Enums\Currency;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'trial_days' => 'nullable|integer|min:0',
            'prices' => 'nullable|array',
            'prices.*.id' => 'required_with:prices|exists:plan_prices,id',
            'prices.*.price' => 'required_with:prices|numeric|min:0',
            'prices.*.currency' => 'required_with:prices|in:'.implode(',', Currency::values()),
            'prices.*.billing_cycle' => 'required_with:prices|in:'.implode(',', BillingCycle::values()),
        ];
    }
}
