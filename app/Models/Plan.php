<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'trial_days'
    ];

    public function prices()
    {
        return $this->hasMany(PlanPrice::class , 'plan_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class , 'plan_id');
    }
}
