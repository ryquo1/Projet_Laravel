<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BoatRequest;
use App\Http\Resources\Api\BoatResource;
use App\Models\web\AcnBoat;
use Exception;
use Illuminate\Http\Request;

class AcnBoatController extends Controller
{
    /**
     * Display a listing of the boats.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BoatResource::collection(AcnBoat::all()->where("BOA_DELETED", 0));
    }

    /**
     * Store a newly created boat in storage.
     *
     * @param  \App\Http\Requests\Api\BoatRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BoatRequest $request)
    {
        $boat = new AcnBoat();
        $boat->BOA_NAME = strtoupper($request->name);
        $boat->BOA_CAPACITY = $request->capacity;
        $boat->save();
        return new BoatResource($boat);
    }

    /**
     * Display the specified boat.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $site = AcnBoat::findOrFail($id);
            if ($site->SIT_DELETED === 1) return response(["message" => "Resource requested does not exist."], 404);
            return new BoatResource($site);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Update the specified boat in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Not using BoatRequest since the check by Laravel for unique doesn't take count of the case of the update
        // trying to apply the same name to the unique entry which has it
        $request->validate([
            "name" => "string|required|max:128",
            "capacity" => "integer|numeric|required|min:4",
        ]);
        try {
            $boat = AcnBoat::findOrFail($id);
            if (AcnBoat::where("BOA_NAME", strtoupper($request->name))->where("BOA_NUM_BOAT", "!=", $id)->exists()) {
                $response = array();
                $response["message"] = "The given data was invalid.";
                $response["errors"] = array("name" => __("validation.unique", ["attribute" => "name"]));
                return response()->json($response, 422);
            }
            if ($boat->BOA_DELETED === 1) return response(["message" => "Resource requested does not exist."], 404);
            $boat->BOA_NAME = strtoupper($request->name);
            $boat->BOA_CAPACITY = $request->capacity;
            $boat->save();
            return new BoatResource($boat);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Remove the specified boat from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $boat = AcnBoat::findOrFail($id);
            $boat->BOA_DELETED = 1;
            $boat->save();
            return response(null, 204);
        } catch (Exception $e) {
            return response("Resource requested to delete does not exist.", 404);
        }
    }
}
