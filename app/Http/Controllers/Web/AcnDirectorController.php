<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\web\AcnMember;
use App\Models\web\AcnDives;
use App\Models\web\AcnPrerogative;
use App\Models\web\AcnSite;
use App\Models\web\AcnPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AcnDirectorController extends Controller
{
    /**
     * retuns the view addMember to add members to a dive
     *
     * @param int $diveNum ->the identification of the dive
     * @return mixed -> the view for adding a new dive with his parameters
     */
    public static function addDiveMemberView($diveNum) {
        $dive = AcnDives::find($diveNum);
        if ($dive->leader->MEM_NUM_MEMBER != auth()->user()->MEM_NUM_MEMBER) {
            return redirect()->route('welcome');
        }
        $members = AcnDives::getMembersNotInDive($diveNum);
        $levels = array();
        foreach($members as $member) {
            $memberPriority = AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBER);
            array_push($levels, AcnPrerogative::getPrerogLabel($memberPriority));
        }
        $registeredMembers = $dive->divers;

        //is the director register
        $directorRegistered = $registeredMembers->contains("MEM_NUM_MEMBER", $dive['DIV_NUM_MEMBER_LEAD']);

        //does the dive still have remaining places
        $maxReached = $registeredMembers->where("MEM_NUM_MEMBER", '!=', $dive['DIV_NUM_MEMBER_LEAD'])->count()==$dive['DIV_MAX_REGISTERED'];
        return view('director/addDiveMember', ["members" => $members, "dive" => $dive, "directorRegistered" => $directorRegistered,
        "maxReached" => $maxReached, 'levels' => $levels]);

    }

    /**
     * Get the dive's informations to a director
     *
     * @param $diveNum the identification of the dive
     * @return mixed -> view with all the information of a dive
     */
    public static function diveInformation($diveNum) {
        $dive = AcnDives::find($diveNum);
        if ($dive->leader->MEM_NUM_MEMBER != auth()->user()->MEM_NUM_MEMBER) {
            return redirect()->route('welcome');
        }
        $allMembers = AcnDives::find($diveNum)->divers;
        $members = array();
        $levels = array();
        $divDate = Carbon::parse($dive['DIV_DATE']) -> startOfDay();
        $today = Carbon::now()->startOfDay();
        $updatable = $divDate->diffInDays($today);
        foreach($allMembers as $member) {
            if (!($member->MEM_NUM_MEMBER == $dive['DIV_NUM_MEMBER_LEAD'])) {
                array_push($members, $member);
                $memberPriority = AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBER);
                array_push($levels, AcnPrerogative::getPrerogLabel($memberPriority));
            }
        }
        $nbMembers = count($members);
        $period = AcnPeriod::find($dive['DIV_NUM_PERIOD']);
        $period = "de ".$period->PER_START_TIME->format('H')."h à ".$period->PER_END_TIME->format('H')."h";

        if (is_null($dive['DIV_NUM_SITE'])) {
            $site = "non définit";
        } else {
            $site = AcnSite::getSite($dive['DIV_NUM_SITE'])->SIT_NAME;
        }

        if (is_null($dive['DIV_NUM_MEMBER_SECURED'])) {
            $selectedSecurity = "non définit";
        } else {
            $selectedSecurity = AcnMember::getMember($dive['DIV_NUM_MEMBER_SECURED']);
            $selectedSecurity = $selectedSecurity->MEM_NAME." ".$selectedSecurity->MEM_SURNAME;
        }
        if (is_null($dive['DIV_NUM_MEMBER_LEAD'])) {
            $selectedLead = "non définit";
        } else {
            $selectedLead = AcnMember::getMember($dive['DIV_NUM_MEMBER_LEAD']);
            $selectedLead = $selectedLead->MEM_NAME." ".$selectedLead->MEM_SURNAME;
        }
        if (is_null($dive['DIV_NUM_MEMBER_PILOTING'])) {
            $selectedPilot = "non définit";
        } else {
            $selectedPilot = AcnMember::getMember($dive['DIV_NUM_MEMBER_PILOTING']);
            $selectedPilot = $selectedPilot->MEM_NAME." ".$selectedPilot->MEM_SURNAME;
        }

        $min_divers = $dive['DIV_MIN_REGISTERED'];
        $max_divers = $dive['DIV_MAX_REGISTERED'];

        return view('director/diveInformation', ['members' => $members, 'dive' => $dive, 'site' => $site, 'period' => $period,
        'security' => $selectedSecurity, 'lead' => $selectedLead, 'pilot' => $selectedPilot, 'min_divers' => $min_divers,
        'max_divers' => $max_divers, 'nbMembers' => $nbMembers, 'levels' => $levels, 'updatable' => $updatable]);
    }

    public static function myDirectorDives() {
        $dives = AcnDives::getDirectorDives(auth()->user()->MEM_NUM_MEMBER);
        $sites = array();
        $prerogatives = array();
        $periods = array();
        foreach($dives as $dive) {
            $site = AcnSite::find($dive->DIV_NUM_SITE);
            if (is_null($site)) {
                $site = "non définit";
            } else {
                $site = $site->SIT_NAME." (".$site->SIT_DESCRIPTION.")";
            }
            array_push($sites, $site);

            $prerogative = AcnPrerogative::find($dive->DIV_NUM_PREROG);
            if (is_null($prerogative)) {
                $prerogative = "non définit";
            } else {
                $prerogative = $prerogative->PRE_LABEL;
            }
            array_push($prerogatives, $prerogative);

            $period = AcnPeriod::find($dive->DIV_NUM_PERIOD);
            array_push($periods, $period);
        }

        return view('director/myDirectorDives', ['dives' => $dives, 'sites' => $sites, 'prerogatives' => $prerogatives, 'periods' => $periods]);
    }


}
