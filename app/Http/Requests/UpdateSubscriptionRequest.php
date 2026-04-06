<?php

namespace App\Http\Requests;

use App\Enums\SubscriptionStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
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
            'status' => 'sometimes|in:'.implode(',', SubscriptionStatus::values()),
            'trial_ends_at' => 'nullable|date',
            'grace_period_ends_at' => 'nullable|date',
            'canceled_at' => 'nullable|date',
        ];
    }
}
