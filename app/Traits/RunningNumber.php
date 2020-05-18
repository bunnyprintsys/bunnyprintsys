<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;


trait RunningNumber
{
    public function getRunningNumByYearMonth($runningNumber = null)
    {
        $yearMonthStr = Carbon::today()->format('ym');

        if ($runningNumber == null) { // if runningNumber is null then initial current yearMonth + 1000, eg 20051000
            $runningNumber = (int) $yearMonthStr . '1000';
        } else {
            if (substr($runningNumber, 0, 4) != $yearMonthStr) { // if current running number year is not current year month
                $runningNumber = (int) $yearMonthStr . '1000'; // // re-initial current year + 1000, eg 20051000
            }
        }

        return $runningNumber + 1;
    }
}
