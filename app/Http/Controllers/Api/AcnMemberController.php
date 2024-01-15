<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MemberRequest;
use App\Http\Resources\Api\MemberResource;
use App\Models\web\AcnDives;
use App\Models\web\AcnFunction;
use App\Models\web\AcnMember;
use App\Models\web\AcnPrerogative;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AcnMemberController extends Controller
{
    /**
     * Display a listing of the members.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MemberResource::collection(AcnMember::all());
    }

    /**
     * Store a newly created member in storage.
     *
     * @param  \App\Http\Requests\Api\MemberRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberRequest $request)
    {
        $member = new AcnMember;
        $member->MEM_NUM_LICENCE = strtoupper($request->licence);
        $member->MEM_NAME = $request->name;
        $member->MEM_SURNAME = $request->surname;
        $member->MEM_DATE_CERTIF = Carbon::parse($request->date_certification);
        $member->MEM_PRICING = strtolower($request->pricing);
        $member->MEM_STATUS = 1;
        $member->MEM_REMAINING_DIVES = 99;
        $member->MEM_PASSWORD = Hash::make($request->password);
        $member->MEM_SUBDATE = Carbon::parse($request->subdate);
        $member->save();
        $functionId = AcnFunction::where("FUN_LABEL", "Adhérent")->first()->FUN_NUM_FUNCTION;
        DB::insert("INSERT INTO ACN_WORKING (NUM_MEMBER, NUM_FUNCTION) values (?, ?)", [$member->MEM_NUM_MEMBER, $functionId]);
        return new MemberResource($member);
    }

    /**
     * Store a newly prerogative for a given member in storage.
     *
     * @param  int  $memberId
     * @param  int  $prerogativeId
     * @return \Illuminate\Http\Response
     */
    static public function storeMemberPrerogative($memberId, $prerogativeId)
    {
        $member = null;
        try {
            $member = AcnMember::findOrFail($memberId);
        } catch (Exception $e) {
            return response(["message" => "Member given does not exist."], 404);
        }
        try {
            AcnPrerogative::findOrFail($prerogativeId);
        } catch (Exception $e) {
            return response(["message" => "Prerogative given does not exist."], 404);
        }
        if($member->prerogatives->contains("PRE_NUM_PREROG", $prerogativeId)) {
            return response(["message" => "The member has already the prerogative given."], 404);
        }
        DB::insert("INSERT INTO ACN_RANKED (NUM_PREROG, NUM_MEMBER) values (?, ?)", [$prerogativeId, $memberId]);
        return response(["message" => "OK"], 200);
    }

    /**
     * Store a newly prerogative for a given member in storage.
     *
     * @param  int  $memberId
     * @param  int  $prerogativeId
     * @return \Illuminate\Http\Response
     */
    static public function storeMemberFunction($memberId, $functionId)
    {
        $member = null;
        try {
            $member = AcnMember::findOrFail($memberId);
        } catch (Exception $e) {
            return response(["message" => "Member given does not exist."], 404);
        }
        try {
            AcnFunction::findOrFail($functionId);
        } catch (Exception $e) {
            return response(["message" => "Function given does not exist."], 404);
        }
        if($member->functions->contains("PRE_NUM_PREROG", $functionId)) {
            return response(["message" => "The member has already the function given."], 404);
        }
        DB::insert("INSERT INTO ACN_WORKING (NUM_FUNCTION, NUM_MEMBER) values (?, ?)", [$functionId, $memberId]);
        return response(["message" => "OK"], 200);
    }

    /**
     * Display the specified member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $member = AcnMember::findOrFail($id);
            return new MemberResource($member);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Update the specified member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "string|required|max:64",
            "surname" => "string|required|max:64",
            "date_certification" => "date|required",
            "pricing" => "string|required|in:adulte,enfant",
            "remaining_dives" => "integer|numeric",
            "password" => "string|sometimes",
            "subdate" => "date|required",
        ]);
        try {
            $member = AcnMember::findOrFail($id);
            if ($member->MEM_STATUS === 0) return response(["message" => "Resource requested does not exist."], 404);
            $member->MEM_NAME = $request->name;
            $member->MEM_SURNAME = $request->surname;
            $member->MEM_DATE_CERTIF = Carbon::parse($request->date_certification);
            $member->MEM_PRICING = strtolower($request->pricing);
            $member->MEM_SUBDATE = strtolower($request->subdate);
            if (isset($request->remaining_dives)) $member->MEM_REMAINING_DIVES = $request->remaining_dives;
            if (isset($request->password)) $member->MEM_PASSWORD = Hash::make($request->password);
            $member->save();
            return new MemberResource($member);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Remove the specified member from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $member = AcnMember::findOrFail($id);
            $member->MEM_STATUS = 0;
            $member->save();
            return response(null, 204);
        } catch (Exception $e) {
            return response("Resource requested to delete does not exist.", 404);
        }
    }

    /**
     * Remove the specified prerogative for the member given from storage.
     *
     * @param  int  $memberId
     * @param  int  $prerogativeId
     * @return \Illuminate\Http\Response
     */
    static public function deleteMemberPrerogative($memberId, $prerogativeId)
    {
        $member = null;
        try {
            $member = AcnMember::findOrFail($memberId);
        } catch (Exception $e) {
            return response(["message" => "Member given does not exist."], 404);
        }
        try {
            AcnPrerogative::findOrFail($prerogativeId);
        } catch (Exception $e) {
            return response(["message" => "Prerogative given does not exist."], 404);
        }
        if(!$member->prerogatives->contains("PRE_NUM_PREROG", $prerogativeId)) {
            return response(["message" => "The member has not the prerogative."], 404);
        }
        DB::table("ACN_RANKED")->where("NUM_PREROG", $prerogativeId)->where("NUM_MEMBER", $memberId)->delete();
        return response(["message" => "OK"], 200);
    }

    /**
     * Remove the specified function for the member given from storage.
     *
     * @param  int  $memberId
     * @param  int  $functionId
     * @return \Illuminate\Http\Response
     */
    static public function deleteMemberFunction($memberId, $functionId)
    {
        $member = null;
        try {
            $member = AcnMember::findOrFail($memberId);
        } catch (Exception $e) {
            return response(["message" => "Member given does not exist."], 404);
        }
        try {
            AcnFunction::findOrFail($functionId);
        } catch (Exception $e) {
            return response(["message" => "Function given does not exist."], 404);
        }
        if(!$member->functions->contains("FUN_NUM_FUNCTION", $functionId)) {
            return response(["message" => "The member has not the function."], 404);
        }
        DB::table("ACN_WORKING")->where("NUM_FUNCTION", $functionId)->where("NUM_MEMBER", $memberId)->delete();
        return response(["message" => "OK"], 200);
    }
}
