<?php

namespace App\Enums;

enum Currency: string
{
    case USD = 'USD';
    case AED = 'AED';
    case EGP = 'EGP';


    public static function values()
    {
        return array_map(fn($currency) => $currency->value, self::cases());
    }

    public function name(): string
    {
        return match($this) {
            self::USD => 'US Dollar',
            self::AED => 'UAE Durham',
            self::EGP => 'Egyptian Pound',
        };
    }
}
