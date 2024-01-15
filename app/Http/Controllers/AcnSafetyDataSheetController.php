<?php
namespace App\Http\Controllers;

use App\Models\web\AcnDives;
use App\Models\web\AcnGroups;
use App\Models\web\AcnPrerogative;
use Illuminate\Support\Facades\DB;

class AcnSafetyDataSheetController extends Controller {
    
    static public function getSafetySheetDives($diveNum) {
        $dive = AcnDives::find($diveNum);

        $pilot = $dive->pilot;

        $secure = $dive->surfaceSecurity;

        $lead = $dive->leader;

        $registered = $dive->divers;

        $boat = $dive->boat;

        $site = $dive->site;

        $period = $dive->period;

        $groupsNums = DB::table('ACN_REGISTERED')
            ->select('NUM_GROUPS')
            ->distinct('NUM_GROUPS')
            ->where('NUM_DIVE', $diveNum)
            ->get();

        $groupInfo = array();    
        $groups = array();
        $levels = AcnPrerogative::all();
        foreach($groupsNums as $group) {
            $members = AcnGroups::find($group->NUM_GROUPS)->divers;
            $palanquing = AcnGroups::find($group->NUM_GROUPS);
            foreach($members as $member) {
                $member->level = AcnPrerogative::getPrerogLabel($member->prerogatives->max('PRE_PRIORITY'));
            }
            array_push($groups, $members);
            array_push($groupInfo, $palanquing);
        }
        

        return view('safetyDataSheet', ["dives" => $dive, "pilote" => $pilot,
        "secure" => $secure, "lead" => $lead, "diveNum" => $diveNum,
        "boat" => $boat, "site" => $site, "period" => $period, "registered" => $registered, 
        "groups" => $groups, "groupInfo" => $groupInfo, "levels" => $levels]);
    }
}

?>
