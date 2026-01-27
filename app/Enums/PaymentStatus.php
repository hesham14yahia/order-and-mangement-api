<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case SUCCESSFUL = 'successful';
    case FAILED = 'failed';

    public function label()
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SUCCESSFUL => 'Successful',
            self::FAILED => 'Failed',
        };
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isSuccessful(): bool
    {
        return $this === self::SUCCESSFUL;
    }

    public function isFailed(): bool
    {
        return $this === self::FAILED;
    }
}
