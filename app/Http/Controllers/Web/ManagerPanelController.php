<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\web\AcnBoat;
use App\Models\web\AcnMember;
use App\Models\web\AcnSite;

class ManagerPanelController extends Controller
{
    /**
     * Display the manager's panel interface
     *
     * @return mixed view of the manager's panel page
     */
    static public function displayManagerPanel() {
        return view("manager/panel", ["boats" => AcnBoat::getAllBoats(), "sites" => AcnSite::getAllSites(), "members" => AcnMember::all()]);
    }
}
