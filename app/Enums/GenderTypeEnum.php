<?php

namespace App\Enums;

use App\Traits\Utils\HasEnums;

enum PasswordTypeEnum: string
{
    use HasEnums;

    case FEMENINO = 'F';
    case MASCULINO = 'M';
}
