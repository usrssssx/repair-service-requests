<?php

namespace App\Enums;

enum RequestStatus: string
{
    case New = 'new';
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Canceled = 'canceled';

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
