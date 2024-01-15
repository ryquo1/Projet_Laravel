<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Web\Controller;
use App\Http\Requests\Api\GroupsRequest;
use App\Http\Resources\Api\GroupsResource;
use App\Models\web\AcnGroups;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnGroupsController extends Controller
{
    /**
     * Display a listing of the groups.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GroupsResource::collection(AcnGroups::all());
    }

    /**
     * Store a newly created group in storage.
     *
     * @param  \App\Http\Requests\Api\GroupsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupsRequest $request)
    {
        $group = new AcnGroups;
        if (isset($request->expected_depth)) $group->GRP_EXPECTED_DEPTH = $request->expected_depth;
        if (isset($request->expected_duration)) $group->GRP_EXPECTED_DURATION = $request->expected_duration;
        if (isset($request->time_of_immersion)) $group->GRP_TIME_OF_IMMERSION = Carbon::parse($request->time_of_immersion);
        if (isset($request->time_of_emersion)) $group->GRP_TIME_OF_EMERSION = Carbon::parse($request->time_of_emersion);
        if (isset($request->reached_depth)) $group->GRP_REACHED_DEPTH = $request->reached_depth;
        if (isset($request->diving_time)) $group->GRP_DIVING_TIME = $request->diving_time;
        $group->save();
        return new GroupsResource($group);
    }

    /**
     * Display the specified group.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $group = AcnGroups::findOrFail($id);
            return new GroupsResource($group);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Update the specified group in storage.
     *
     * @param  \App\Http\Requests\Api\GroupsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupsRequest $request, $id)
    {
        $group = null;
        try {
            $group = AcnGroups::findOrFail($id);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
        if (isset($request->expected_depth)) $group->GRP_EXPECTED_DEPTH = $request->expected_depth;
        if (isset($request->expected_duration)) $group->GRP_EXPECTED_DURATION = $request->expected_duration;
        if (isset($request->time_of_immersion)) $group->GRP_TIME_OF_IMMERSION = Carbon::parse($request->time_of_immersion);
        if (isset($request->time_of_emersion)) $group->GRP_TIME_OF_EMERSION = Carbon::parse($request->time_of_emersion);
        if (isset($request->reached_depth)) $group->GRP_REACHED_DEPTH = $request->reached_depth;
        if (isset($request->diving_time)) $group->GRP_DIVING_TIME = $request->diving_time;
        $group->save();
        return new GroupsResource($group);
    }

    /**
     * Remove the specified group from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            AcnGroups::findOrFail($id);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
        DB::update("UPDATE ACN_REGISTERED SET NUM_GROUPS=null WHERE NUM_GROUPS = ?", [$id]);
        AcnGroups::where("GRP_NUM_GROUPS", $id)->delete();
        return response(["message" => "OK"], 200);
    }
}