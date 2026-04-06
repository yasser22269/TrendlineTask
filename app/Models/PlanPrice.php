<?php

namespace App\Models;

use App\Enums\BillingCycle;
use App\Enums\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'plan_id',
        'price',
        'currency',
        'billing_cycle'
    ];

    protected $casts = [
        'currency' => Currency::class,
        "billing_cycle" => BillingCycle::class
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class , 'plan_id');
    }


}
