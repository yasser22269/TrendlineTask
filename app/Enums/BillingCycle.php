<?php

namespace App\Enums;

enum BillingCycle: string
{
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';

    public function label(): string
    {
        return match($this) {
            self::MONTHLY => 'Monthly',
            self::YEARLY => 'Yearly',
        };
    }


    public static function values()
    {
        return array_map(fn($BillingCycle) => $BillingCycle->value, self::cases());
    }


}
