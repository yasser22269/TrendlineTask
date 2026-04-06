<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'started_at',
        'trial_ends_at',
        'grace_period_ends_at',
        'canceled_at'
    ];

    protected $casts = [
        'status' => SubscriptionStatus::class,
        'started_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'grace_period_ends_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class , 'plan_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class , 'subscription_id');
    }

    /**
     * Lifecycle Status Scopes
     */
    public function scopeTrialing(Builder $query): Builder
    {
        return $query->where('status', SubscriptionStatus::TRIALING);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', SubscriptionStatus::ACTIVE);
    }

    public function scopePastDue(Builder $query): Builder
    {
        return $query->where('status', SubscriptionStatus::PAST_DUE);
    }

    public function scopeCanceled(Builder $query): Builder
    {
        return $query->where('status', SubscriptionStatus::CANCELED);
    }

    public function scopeAccessible(Builder $query): Builder
    {
        return $query->whereNot('status', SubscriptionStatus::CANCELED);
    }

    /**
     * Check if subscription is in trial period
     */
    public function isTrialing(): bool
    {
        return $this->status === SubscriptionStatus::TRIALING;
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === SubscriptionStatus::ACTIVE;
    }

    /**
     * Check if subscription is past due
     */
    public function isPastDue(): bool
    {
        return $this->status === SubscriptionStatus::PAST_DUE;
    }

    /**
     * Check if subscription is canceled
     */
    public function isCanceled(): bool
    {
        return $this->status === SubscriptionStatus::CANCELED;
    }

    /**
     * Check if user has access (not canceled)
     */
    public function hasAccess(): bool
    {
        return $this->status !== SubscriptionStatus::CANCELED;
    }

    /**
     * Check if trial is expired
     */
    public function isTrialExpired(): bool
    {
        return $this->trial_ends_at && now()->isAfter($this->trial_ends_at);
    }

    /**
     * Check if grace period is expired
     */
    public function isGracePeriodExpired(): bool
    {
        return $this->grace_period_ends_at && now()->isAfter($this->grace_period_ends_at);
    }

    /**
     * Get days remaining in trial
     */
    public function getTrialDaysRemaining(): int
    {
        if (!$this->trial_ends_at) {
            return 0;
        }

        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    /**
     * Get days remaining in grace period
     */
    public function getGracePeriodDaysRemaining(): int
    {
        if (!$this->grace_period_ends_at) {
            return 0;
        }

        return max(0, now()->diffInDays($this->grace_period_ends_at, false));
    }

    /**
     * Get the last successful payment
     */
    public function getLastSuccessfulPayment()
    {
        return $this->payments()
            ->where('status', 'completed')
            ->orderBy('paid_at', 'desc')
            ->first();
    }

    /**
     * Check if subscription has any successful payment
     */
    public function hasSuccessfulPayment(): bool
    {
        return $this->payments()
            ->where('status', 'completed')
            ->exists();
    }
}
