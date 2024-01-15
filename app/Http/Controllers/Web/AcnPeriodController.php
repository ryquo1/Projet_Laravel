<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnPeriodController extends Controller
{
    /**
     * Get all the period's name
     *
     * @param number $numPeriod the identification of the period
     * @return list of the period names
     */
    static public function getPeriodName($numPeriod) {
        $name = DB::table('ACN_PERIOD')
            -> select('PER_LABEL')
            -> where('PER_NUM_PERIOD', $numPeriod)
            -> get();

        $name = (array) $name[0];
        return $name['PER_LABEL'];
    }
}
