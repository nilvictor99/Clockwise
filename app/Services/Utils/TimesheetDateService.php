<?php

namespace App\Services\Utils;

use App\Services\Service;
use Carbon\Carbon;

class TimesheetDateService extends Service
{
    public function formatWorkDate($dayIn, $dayOut)
    {
        $dateIn = Carbon::parse($dayIn)->format('Y-m-d');
        $dateOut = Carbon::parse($dayOut)->format('Y-m-d');

        return $dateIn === $dateOut ? $dateIn : "$dateIn - $dateOut";
    }
}
