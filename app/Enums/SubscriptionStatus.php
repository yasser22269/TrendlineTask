<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case TRIALING = 'trialing';
    case ACTIVE = 'active';
    case PAST_DUE = 'past_due';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match($this) {
            self::TRIALING => 'Trial Period',
            self::ACTIVE => 'Active',
            self::PAST_DUE => 'Past Due',
            self::CANCELED => 'Canceled',
        };
    }

    public static function values()
    {
        return array_map(fn($SubscriptionStatus) => $SubscriptionStatus->value, self::cases());
    }

}
