<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';

    case SUCCESS = 'success';
    case FAILED = 'failed';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::SUCCESS => 'Success',
            self::FAILED => 'Failed',
        };
    }


    public static function values()
    {
        return array_map(fn($PaymentStatus) => $PaymentStatus->value, self::cases());
    }


}
