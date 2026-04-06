<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'plan_id' => $this->plan_id,
            'status' => $this->status,
            'started_at' => $this->started_at,
            'trial_ends_at' => $this->trial_ends_at,
            'grace_period_ends_at' => $this->grace_period_ends_at,
            'canceled_at' => $this->canceled_at,
            'plan' => new PlanResource($this->whenLoaded('plan')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
