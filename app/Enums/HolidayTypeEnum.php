<?php

namespace App\Enums;

use App\Traits\Utils\HasEnums;

enum HolidayTypeEnum: string
{
    use HasEnums;

    case VACATION = 'vacation';
    case SICK_LEAVE = 'sick_leave';
    case PERSONAL_LEAVE = 'personal_leave';
    case MATERNITY_LEAVE = 'maternity_leave';
    case PATERNITY_LEAVE = 'paternity_leave';
}
