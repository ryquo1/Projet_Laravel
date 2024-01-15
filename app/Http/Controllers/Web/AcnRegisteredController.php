<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\web\AcnRegistered;

class AcnRegisteredController extends Controller
{
    /**
     * Create member in a register
     *
     * @param number $numMember the identification of the member
     * @param number $numDive   the identification of the dive
     *
     */
    public static function create($numMember, $numDive) {
        AcnRegistered::insert($numMember, $numDive);
    }

    /**
     * Delete a member of the register
     *
     * @param number $numMember the identification of the member
     * @param number $numDive the identitification of the dive
     *
     */
    public static function delete($numMember, $numDive) {
        AcnRegistered::deleteData($numMember, $numDive);
    }
}
