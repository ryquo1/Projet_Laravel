<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\web\AcnBoat;
use App\Models\web\AcnDives;
use App\Models\web\AcnSite;
use App\Models\web\AcnPeriod;
use App\Models\web\AcnMember;
use App\Models\web\AcnFunction;
use App\Models\web\AcnPrerogative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Web\AcnDivesController;
use App\Http\Controllers\Web\AcnBoatController;
use Carbon\Carbon;

class AcnDiveCreationController extends Controller
{
    /**
     * Get all the dive's informations
     *
     * @return mixed view with all the parameters of a dive
     */
    static public function getAll() {
        $boats = AcnBoat::all();
        $sites = AcnSite::all();
        $periods = AcnPeriod::all();
        $prerogatives = DB::table('ACN_PREROGATIVE') -> where('PRE_LEVEL', 'not like', 'E%') -> get();

        $leads = AcnMember::getAllLeader();

        $pilots = AcnMember::getAllPilots();

        $securitys = AcnMember::getAllSecurity();
        return view ('diveCreation', ["boats" => $boats, "sites" => $sites, "periods" => $periods, "prerogatives" => $prerogatives, "leads" => $leads,"pilots" => $pilots, "securitys" => $securitys]);
    }

    /**
     * Create a dive
     *
     * @param Request $request the request to create a dive
     *
     */
    static public function create(Request $request) {

        //creation of the error variable
        $err = false;
        //ceation of the error message
        $strErr = "";

        //check if a dive already exists at the date and period
        $exist = AcnDivesController::existDive($request -> date, $request -> period);
        if ($exist) {
            //if it exists sets the error variable to true and add a message to the error message
            $err = true;
            $periodName = AcnPeriodController::getPeriodName($request -> period);
            $strErr .= "- Une plongée existe déjà le ".Carbon::parse($request -> date)->locale('fr_FR')->translatedFormat('l j F Y')." à ce moment "."(".$periodName.").;";
        }

        //if the boat exists, check if the max_divers is lower than the capacity -3 (-3 for the pilot, the surface security and the dive's diector)
        if (!is_null($request->boat)) {
            $capacity = AcnBoat::getBoatDiversCapacity($request->boat);
            //if the max_divers is not specified, it is set at the maximum of divers the boat can carry
            if ($request -> max_divers == 0) {
                $request -> max_divers = AcnBoat::getBoatDiversCapacity($request -> boat);
            }
            else if ($capacity < ($request -> max_divers) ) {
                //if the max_divers is superior to the capacity, sets the erro variable to true and add a message to the error message
                $boatName = AcnBoat::getBoatName($request -> boat);
                $err = true;
                $strErr .= "- Le nombre de nageurs maximal renseigné est supérieur à la capacité en plongeur du bateau ".$boatName." (".$capacity." plongeur max).;";
            }
        }
        else {
            //If the boat isn't specified, the capacity of divers of the biggest boat is retrieved
            $capacity = AcnBoat::getBoatDiversCapacity();
            //if the max_divers is not specified, it is set at the maximum of divers the biggest boat can carry
            if ($request -> max_divers == 0) {
                $request -> max_divers = $capacity;
            }
            //If it is specified, check if the max_divers is lower than the capacity of the biggest boat
            else if ($capacity < $request -> max_divers) {
                $err = true;
                $strErr .= "- Le nombre de nageurs maximal renseigné est supérieur à la capacité en plongeur du bateau le plus gros (".$capacity.").;";
            }
        }

        //Check if the min_divers is lower than the max_divers
        if ($request -> min_divers > $request -> max_divers) {
            $err = true;
            $strErr .="- Le nombe minimum de nageurs ne peut être supérieur au nombre maximum.;";
        }

        //Checks if the leader, the pilot and the surface security are different person.
        if (!is_null($request -> lead) && !is_null($request -> pilot) && ($request -> lead == $request -> pilot) ) {
            $member = AcnMember::getMember($request -> lead);
            $err = true;
            $strErr .= "- Le directeur de plongée et le pilote ne peuvent être la même personne (".$member->MEM_NAME." ".$member->MEM_SURNAME.").;";
        }
        if (!is_null($request -> lead) && !is_null($request -> security) && ($request -> lead == $request -> security) ) {
            $member = AcnMember::getMember($request -> lead);
            $err = true;
            $strErr .= "- Le directeur de plongée et la sécurié de surface ne peuvent être la même personne (".$member->MEM_NAME." ".$member->MEM_SURNAME.").;";
        }
        if (!is_null($request -> pilot) && !is_null($request -> security) && ($request -> pilot == $request -> security) ) {
            $member = AcnMember::getMember($request -> pilot);
            $err = true;
            $strErr .= "- La sécurié de surface et le pilote ne peuvent être la même personne (".$member->MEM_NAME." ".$member->MEM_SURNAME.").;";
        }
        if(Carbon::createFromFormat('Y-m-d' , $request -> date)->dayOfWeekIso == 7 &&  $request -> period != 1 ){
            $err = true;
            $strErr .= "- Vous ne pouvez pas créer de plongée l'après midi ou le soir un dimanche;";
        }

        if ($err) {
            $arrayErr = explode(";",$strErr);
            return view('diveException',['error_msg'=>$arrayErr]);
        }
        else {
            AcnDives::create($request -> date, $request -> period, $request -> site, $request -> boat, $request -> lvl_required,
            $request -> lead, $request -> pilot, $request -> security, $request -> min_divers, $request -> max_divers);
            return redirect ('dives');
        }

    }
}
