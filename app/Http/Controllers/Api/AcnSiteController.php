<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SiteRequest;
use App\Http\Resources\Api\SiteResource;
use App\Models\web\AcnSite;
use Exception;
use Illuminate\Http\Request;

class AcnSiteController extends Controller
{
    /**
     * Display a listing of the sites.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SiteResource::collection(AcnSite::all()->where("SIT_DELETED", 0));
    }

    /**
     * Store a newly created site in storage.
     *
     * @param  \App\Http\Requests\Api\SiteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SiteRequest $request)
    {
        $site = new AcnSite;
        $site->SIT_NAME = strtoupper($request->name);
        $site->SIT_COORD = $request->coord;
        $site->SIT_DEPTH = $request->depth;
        $site->SIT_DESCRIPTION = $request->description;
        $site->save();
        return new SiteResource($site);
    }

    /**
     * Display the specified site.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $site = AcnSite::findOrFail($id);
            if ($site->SIT_DELETED === 1) return response(["message" => "Resource requested does not exist."], 404);
            return new SiteResource($site);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }

    }

    /**
     * Update the specified site in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Not using SiteRequest since the check by Laravel for unique doesn't take count of the case of the update
        // trying to apply the same name to the unique entry which has it
        $request->validate([
            "name" => "string|required|max:128",
            "coord" => "string|required|max:128",
            "depth" => "integer|numeric|required",
            "description" => "string|max:256",
        ]);
        try {
            $site = AcnSite::findOrFail($id);
            if (AcnSite::where("SIT_NAME", strtoupper($request->name))->where("SIT_NUM_SITE", "!=", $id)->exists()) {
                $response = array();
                $response["message"] = "The given data was invalid.";
                $response["errors"] = array("name" => __("validation.unique", ["attribute" => "name"]));
                return response()->json($response, 422);
            }
            if ($site->SIT_DELETED === 1) return response(["message" => "Resource requested does not exist."], 404);
            $site->SIT_NAME = strtoupper($request->name);
            $site->SIT_COORD = $request->coord;
            $site->SIT_DEPTH = $request->depth;
            $site->SIT_DESCRIPTION = $request->description;
            $site->save();
            return new SiteResource($site);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Remove the specified site from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $site = AcnSite::findOrFail($id);
            $site->SIT_DELETED = 1;
            $site->save();
            return response(null, 204);
        } catch (Exception $e) {
            return response("Resource requested to delete does not exist.", 404);
        }
    }
}
