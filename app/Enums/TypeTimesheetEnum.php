<?php

namespace App\Enums;

use App\Traits\Utils\HasEnums;

enum TypeTimesheetEnum: string
{
    use HasEnums;

    case BREAK = 'break';
    case WORK = 'work';
}
