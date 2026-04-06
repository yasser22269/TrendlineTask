<?php

namespace App\Models;

use App\Enums\Currency;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'amount',
        'currency',
        'status',
        'paid_at'
    ];


    protected $casts = [
        'currency' => Currency::class,
        'status' => PaymentStatus::class,
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class  , 'subscription_id');
    }


}
