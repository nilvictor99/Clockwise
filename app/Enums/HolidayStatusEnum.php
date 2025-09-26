<?php

namespace App\Enums;

use App\Traits\Utils\HasEnums;

enum HolidayStatusEnum: string
{
    use HasEnums;

    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
