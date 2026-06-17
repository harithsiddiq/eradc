<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => __('Active'),
            self::EXPIRED => __('Expired'),
            self::SUSPENDED => __('Suspended'),
        };
    }
}
