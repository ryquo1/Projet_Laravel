<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AcnRegisteredController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DivesRequest;
use App\Http\Resources\Api\DivesResource;
use App\Models\web\AcnBoat;
use App\Models\web\AcnDives;
use App\Models\web\AcnGroups;
use App\Models\web\AcnMember;
use App\Models\web\AcnPeriod;
use App\Models\web\AcnSite;
use Exception;
use Illuminate\Support\Facades\DB;

class AcnDivesController extends Controller
{
    /**
     * Display a listing of the dives.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DivesResource::collection(AcnDives::all());
    }

    /**
     * Function used to check if the member of the given id exist and
     * if this one has the required role
     *
     * @param int $id
     * @param string $userFunctionString
     * @retrun boolean
     */
    static private function checkMemberFunction(?int $id, string $userFunctionString) {
        if (!isset($id)) return true;
        $member = null;
        try {
            $member = AcnMember::findOrFail($id);
        } catch (Exception $e) { return false; }
        if (strcmp($userFunctionString, "Leader")) {
            return $member->prerogatives->max("PRE_PRIORITY") > 13;
        }
        return $member->functions->contains("FUN_LABEL", $userFunctionString);
    }

    /**
     * Function used to update or create a given dive with the
     * request's parameters.
     *
     * @param \App\Http\Requests\Api\DivesRequest  $request
     * @param \App\Models\web\AcnDives $dive
     * @return \Illuminate\Http\Response
     */
    static private function updateOrCreateDive(DivesRequest $request, AcnDives $dive) {
        if (isset($request->min_registered)) $dive->DIV_MIN_REGISTERED = $request->min_registered;
        if (isset($request->max_registered)) $dive->DIV_MAX_REGISTERED = $request->max_registered;
        if (isset($request->observation)) $dive->DIV_OBSERVATION = $request->observation;
        try{AcnBoat::find($request->boat); } catch (Exception $e) {
            return response(["message" => "Boat given does not exist."], 404);
        }
        $dive->DIV_NUM_BOAT = $request->boat;
        try{AcnSite::find($request->site); } catch (Exception $e) {
            return response(["message" => "Site given does not exist."], 404);
        }
        $dive->DIV_NUM_SITE = $request->site;
        if (!self::checkMemberFunction($request->surface_security, "Sécurité de surface"))
            return response(["message" => "Surface security given does not exist."], 404);
        if (!self::checkMemberFunction($request->pilot, "Pilote"))
            return response(["message" => "Pilot given does not exist."], 404);
        if (!self::checkMemberFunction($request->leader, "Leader"))
            return response(["message" => "Leader given does not exist."], 404);
        $dive->DIV_NUM_MEMBER_SECURED = $request->surface_security;
        $dive->DIV_NUM_MEMBER_LEAD = $request->leader;
        $dive->DIV_NUM_MEMBER_PILOTING = $request->pilot;
        $dive->save();
        return new DivesResource($dive);
    }

    /**
     * Store a newly created dive in storage.
     *
     * @param  \App\Http\Requests\Api\DivesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DivesRequest $request)
    {
        $dive = new AcnDives;
        $dive->DIV_DATE = $request->date;
        try{ AcnPeriod::findOrFail($request->period); } catch (Exception $e) {
            return response(["message" => "Period given does not exist."], 404);
        }
        $dive->DIV_NUM_PERIOD = $request->period;
        return self::updateOrCreateDive($request, $dive);
    }

    /**
     * Display the specified dive.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $dive = AcnDives::findOrFail($id);
            return new DivesResource($dive);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Update the specified dive in storage.
     *
     * @param  \App\Http\Requests\Api\DivesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DivesRequest $request, $id)
    {
        try {
            $dive = AcnDives::findOrFail($id);
            return self::updateOrCreateDive($request, $dive);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Remove the specified dive from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dive = null;
        try {
            $dive = AcnDives::findOrFail($id);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
        DB::delete("DELETE FROM ACN_REGISTERED WHERE NUM_DIVE = ?", [$id]);
        $dive->delete();
        return response(["message" => "OK"], 200);
    }

    /**
     * Register a member in a dive if possible.
     *
     * @param  int  $diveId
     * @param  int  $memberId
     * @return \Illuminate\Http\Response
     */
    static public function registerMemberInDive(int $diveId, int $memberId) {
        $dive = null;
        try { $dive = AcnDives::findOrFail($diveId); } catch (Exception $e) {
            return response(["message" => "The dive given does not exist."], 404);
        }
        $member = null;
        try { $member = AcnMember::findOrFail($memberId); } catch (Exception $e) {
            return response(["message" => "The member given does not exist."], 404);
        }
        if (!isset($dive->prerogative) || !isset($dive->site))
            return response(["message" => "The dive is not ready to add members for now."], 403);
        if ($dive->prerogative->PRE_PRIORITY > $member->prerogatives->max("PRE_PRIORITY"))
            return response(["message" => "The diver has not enough level to register in the dive."], 403);
        if ($dive->divers->where("MEM_NUM_MEMBER", "!=",$dive->leader->MEM_NUM_MEMBER)->count() === $dive->DIV_MAX_REGISTERED)
            return response(["message" => "The dive has reached its register limit."], 403);
        AcnRegisteredController::create($memberId, $diveId);
        return response(["message" => "OK"], 200);
    }

    /**
     * Unregister a member from a dive if possible.
     *
     * @param  int  $diveId
     * @param  int  $memberId
     * @return \Illuminate\Http\Response
     */
    static public function unregisterMemberInDive(int $diveId, int $memberId) {
        $dive = null;
        try { $dive = AcnDives::findOrFail($diveId); } catch (Exception $e) {
            return response(["message" => "The dive given does not exist."], 404);
        }
        $member = null;
        try { $member = AcnMember::findOrFail($memberId); } catch (Exception $e) {
            return response(["message" => "The member given does not exist."], 404);
        }
        $entryExist = DB::table("ACN_REGISTERED")->where("NUM_DIVE", $diveId)->where("NUM_MEMBER", $memberId)->exists();
        if (!$entryExist) return response(["message" => "The member is not registered for this dive."], 404);
        AcnRegisteredController::delete($memberId, $diveId);
        return response(["message" => "OK"], 200);
    }
    /**
     * Register a member of a dive in a group if possible.
     *
     * @param  int  $diveId
     * @param  int  $memberId
     * @param  int  $groupId
     * @return \Illuminate\Http\Response
     */
    static public function registerMemberFromDiveInGroup(int $diveId, int $memberId, int $groupId) {
        try { AcnDives::findOrFail($diveId); } catch (Exception $e) {
            return response(["message" => "The dive given does not exist."], 404);
        }
        try { AcnMember::findOrFail($memberId); } catch (Exception $e) {
            return response(["message" => "The member given does not exist."], 404);
        }
        try { AcnGroups::findOrFail($groupId); } catch (Exception $e) {
            return response(["message" => "The group given does not exist."], 404);
        }
        $isMemberRegistered = DB::table('ACN_REGISTERED')->where("NUM_DIVE", $diveId)->where("NUM_MEMBER", $memberId)->exists();
        if ($isMemberRegistered) {
            DB::table("ACN_REGISTERED")->where("NUM_DIVE", $diveId)->where("NUM_MEMBER", $memberId)->update(["NUM_GROUPS" => $groupId]);
        } else {
            DB::table("ACN_REGISTERED")->insert([
                "NUM_DIVE" => $diveId,
                "NUM_MEMBER" => $memberId,
                "NUM_GROUPS" => $groupId,
            ]);
        }
        return response(["message" => "OK"], 200);
    }

    /**
     * Unregister a member from a dive if possible.
     *
     * @param  int  $diveId
     * @param  int  $memberId
     * @return \Illuminate\Http\Response
     */
    static public function unregisterMemberFromDiveInGroup(int $diveId, int $memberId) {
        try { AcnDives::findOrFail($diveId); } catch (Exception $e) {
            return response(["message" => "The dive given does not exist."], 404);
        }
        try { AcnMember::findOrFail($memberId); } catch (Exception $e) {
            return response(["message" => "The member given does not exist."], 404);
        }
        $entryExist = DB::table("ACN_REGISTERED")->where("NUM_DIVE", $diveId)->where("NUM_MEMBER", $memberId)->exists();
        if (!$entryExist) return response(["message" => "The member is not registered for this dive."], 404);
        DB::table("ACN_REGISTERED")->where("NUM_DIVE", $diveId)->where("NUM_MEMBER", $memberId)->update(["NUM_GROUPS" => null]);
        return response(["message" => "OK"], 200);
    }
}
