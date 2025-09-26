<?php

namespace App\Enums;

use App\Traits\Utils\HasEnums;

enum PermissionsPasswordEnum: string
{
    use HasEnums;

    case VIEW = 'view';
    case EDIT = 'edit';
}
