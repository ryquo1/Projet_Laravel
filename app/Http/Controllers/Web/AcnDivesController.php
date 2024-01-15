<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\web\AcnDives;
use App\Models\web\AcnMember;
use App\Models\web\AcnBoat;
use App\Models\web\AcnPeriod;
use App\Models\web\AcnPrerogative;
use App\Models\web\AcnRegistered;
use App\Models\web\AcnSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LDAP\Result;
use Carbon\Carbon;

class AcnDivesController extends Controller
{
    /**
     * Get all the dive's values
     *
     * @return mixed the dive and his month
     */
    public static function getAllDivesValues() {
    $months = AcnDives::getMonthWithDive();

        $dives = array();
        foreach ($months as $month) {
        $dive = AcnDives::getDivesOfAMonth($month->mois_nb)->where('DIV_DATE', '>', Carbon::now());
            $dives[$month->mois_mot] = $dive;
        }
        return view("displayDives",["dives" => $dives, "months" => $months]);
    }

    static public function existDive($date, $numPeriod) {
        return DB::table('ACN_DIVES')
            -> select(DB::raw(1))
            -> where('DIV_NUM_PERIOD', $numPeriod)
            -> where('DIV_DATE', '=', date($date))
            -> exists();

    }

    /**
     * Get all dive's informations
     *
     * @param number $id the identification of the dive
     * @return mixed with all the informations of a dive thanks to his id
     */
    public static function getAllDiveInformation($id){
        $dives = AcnDives::find($id);
        $dives_lead = AcnMember::getMember($dives -> DIV_NUM_MEMBER_LEAD);
        if (is_null($dives_lead)) {
            $dives_lead = "non définit";
        } else {
            $dives_lead = $dives_lead->MEM_NAME." ".$dives_lead->MEM_SURNAME;
        }

        $dives_secur = AcnMember::getMember($dives -> DIV_NUM_MEMBER_SECURED);
        if (is_null($dives_secur)) {
            $dives_secur = "non définit";
        } else {
            $dives_secur = $dives_secur->MEM_NAME." ".$dives_secur->MEM_SURNAME;
        }

        $dives_pilot = AcnMember::getMember($dives -> DIV_NUM_MEMBER_PILOTING);
        if (is_null($dives_pilot)) {
            $dives_pilot = "non définit";
        } else {
            $dives_pilot = $dives_pilot->MEM_NAME." ".$dives_pilot->MEM_SURNAME;
        }

        $site = AcnSite::find($dives->DIV_NUM_SITE);
        if (is_null($site)) {
            $site = "non définit";
        } else {
            $site = $site->SIT_NAME." (".$site->SIT_DESCRIPTION.")";
        }

        $boat = AcnBoat::find($dives->DIV_NUM_BOAT);
        if (is_null($boat)) {
            $site = "non définit";
        } else {
            $boat = $boat->BOA_NAME;
        }

        $prerogative = AcnPrerogative::find($dives->DIV_NUM_PREROG);
        if (is_null($prerogative)) {
            $prerogative = "non définit";
        } else {
            $prerogative = $prerogative->PRE_LABEL;
        }

        $period = AcnPeriod::find($dives->DIV_NUM_PERIOD);
        $dives_register = AcnDives::find($id)->divers;

        return view("divesInformation",["dives" => $dives, "dives_lead" => $dives_lead, "dives_secur" => $dives_secur, "dives_pilot" => $dives_pilot, "dives_register"=> $dives_register,
        "prerogative" => $prerogative, "period" => $period, "site" => $site, "boat" => $boat]);
    }

    /**
     * Register a member to a dive
     *
     * @param Request $request the request to register a new diver in a dive
     * @return mixed the redirection after an attempt of a registering
     */
    static public function register(Request $request){
        $userPriority = auth()->user()->prerogatives->max("PRE_PRIORITY");

        $dive = AcnDives::find($request->dive);
        $divePriority = $dive->prerogative->PRE_PRIORITY;

        $errors = array();

        if ($userPriority < $divePriority) {
            $errors["insufficientLevel"] = "Vous ne pouvez pas vous inscrire. Votre niveau est insuffisant.";
        }
        if ($dive->divers->count() === $dive->DIV_MAX_REGISTERED) {
            $errors["max_member_reach"] = "Il n'y a plus de place dans la plongée.";
        }

        if (!empty($errors)) return back()->withErrors($errors);

        $userId = auth()->user()->MEM_NUM_MEMBER;
        AcnRegistered::insert($userId, $request->dive);

        $user = AcnMember::getMember($userId);
        $user->MEM_REMAINING_DIVES = $user->MEM_REMAINING_DIVES - 1;
        $user->save();

        return redirect(route("dives"));
    }

    /**
     * Unregister a member to a dive
     *
     * @param Request $request a request to unregister a diver in a dive
     * @return mixed to the page of dives
     */
    static public function unregister(Request $request){

        $userId = auth()->user()->MEM_NUM_MEMBER;
        AcnRegistered::deleteData(auth()->user()->MEM_NUM_MEMBER, $request->dive);

        $user = AcnMember::getMember($userId);
        $user->MEM_REMAINING_DIVES = $user->MEM_REMAINING_DIVES + 1;
        $user->save();

        return redirect(route("dives"));
    }

    public static function getMemberDivesReport() {

        $numMember = auth()->user()->MEM_NUM_MEMBER ;
        $dives = AcnMember::getMember($numMember)
            ->dives->where("DIV_DATE",'<',Carbon::now())
            ->where("DIV_DATE",'>',Carbon::now()->subYear());
        $periods = array();
        foreach($dives as $dive) {
            $period = AcnPeriod::find($dive->DIV_NUM_PERIOD);
            array_push($periods, $period);
        }

        return view("diveReport", ["dives"=> $dives, "periods"=> $periods]);
    }

    public static function getAllDivesReport() {
        $actualMonth = Carbon::now()->month;

        if($actualMonth < 3){
            $year = Carbon::now()->year-1;
            $startDate = Carbon::createFromDate($year,3,1);
            $endDate = Carbon::createFromDate($year,12,1);
            $dives = AcnDives::all()->where("DIV_DATE",'>',$startDate)->where("DIV_DATE",'<',$endDate);
        }else{
            $year = Carbon::now()->year;
            $startDate = Carbon::createFromDate($year,3,1);
            $dives = AcnDives::all()->where("DIV_DATE",'>',$startDate)->where("DIV_DATE",'<',Carbon::now());
        }
        $periods = array();
        foreach($dives as $dive) {
            $period = AcnPeriod::find($dive->DIV_NUM_PERIOD);
            array_push($periods, $period);
        }

        return view("diveReport", ["dives"=> $dives, "periods"=> $periods]);
    }

    public static function getAllDivesReportIsDirector() {
        $numMember = auth()->user()->MEM_NUM_MEMBER ;
        $dives = AcnDives::getDirectorReport($numMember);
        $periods = array();
        foreach($dives as $dive) {
            $period = AcnPeriod::find($dive->DIV_NUM_PERIOD);
            array_push($periods, $period);
        }

        return view("diveReport", ["dives"=> $dives, "periods"=> $periods]);
    }

    public static function delete($diveId) {
        $divers = AcnDives::find($diveId)->divers;
        foreach($divers as $diver) {
            $diver->MEM_REMAINING_DIVES = $diver->MEM_REMAINING_DIVES +1;
            $diver->save();
        }
        AcnRegistered::deleteDive($diveId);
        AcnDives::find($diveId)->delete();
        return redirect()->route('dives');
    }
    
    public static function getAllArchives() {
        $archives = AcnDives::getArchives();
        foreach($archives as $archive) {
            $archive -> BOAT_NAME = AcnBoat::find($archive -> DIV_NUM_BOAT)->BOA_NAME;
            $archive -> LEVEL = AcnPrerogative::find($archive -> DIV_NUM_BOAT) -> PRE_LEVEL;
            $lead = AcnMember::find($archive -> DIV_NUM_MEMBER_LEAD);
            $security = AcnMember::find($archive -> DIV_NUM_MEMBER_SECURED);
            $pilot = AcnMember::find($archive -> DIV_NUM_MEMBER_PILOTING);
            $archive -> LEADER = ($lead -> MEM_NAME." ". $lead -> MEM_SURNAME);
            $archive -> PILOT = ($security -> MEM_NAME." ". $security -> MEM_SURNAME);
            $archive -> SECURITY = ($pilot -> MEM_NAME." ". $pilot -> MEM_SURNAME);
        }
        return view('archives', ['archives' => $archives]);
    }
}
