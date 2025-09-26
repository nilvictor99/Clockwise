<?php

namespace App\Services\Models;

use App\Repositories\Models\TimesheetRepository;

class TimesheetService extends BaseService
{
    public function __construct(TimesheetRepository $repository)
    {
        parent::__construct($repository);
    }
}
