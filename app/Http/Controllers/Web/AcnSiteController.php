<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\web\AcnSite;
use Illuminate\Http\Request;

class AcnSiteController extends Controller
{
    /**
     * Create a site
     *
     * @param Request $request the request to create a new site
     * @return mixed after an attempt of a site creation
     */
    static public function create(Request $request) {
        $errors = array();
        $nameAlreadyExist = AcnSite::where("SIT_NAME", "=", strtoupper($request->sit_name))->exists();
        if ($nameAlreadyExist) {
            $errors["name"] = "Le nom donné est déjà existant.";
        }
        if (strlen($request->sit_coord) > 127) {
            $errors["coord_len"] = "Les coordonnées ne doivent pas faire plus de 127 caractères.";
        }
        if (strlen($request->sit_description) > 255) {
            $errors["coord_len"] = "La description ne doit pas faire plus de 255 caractères.";
        }
        if (empty($request->sit_name) || empty($request->sit_coord) || empty($request->sit_depth) || empty($request->sit_description)) {
            $errors["empty_entry"] = "Tous les champs doivent êtres remplis.";
        }
        if (count($errors) != 0) return back()->withErrors($errors);
        $site = new AcnSite;
        $site->SIT_NAME = strtoupper($request->sit_name);
        $site->SIT_COORD = $request->sit_coord;
        $site->SIT_DEPTH  = $request->sit_depth;
        $site->SIT_DESCRIPTION  = $request->sit_description;
        $site->save();
        return redirect(route("managerPanel"));
    }

    /**
     * Delete a site
     *
     * @param number $siteId the identification of the site to delete
     *
     */
    static public function delete($siteId) {
        $site = AcnSite::find($siteId);
        $site->SIT_DELETED = 1;
        $site->save();
    }

    /**
     * Update site's informations
     *
     * @param Request $request the request of updating a site
     * @param number $siteId the identification of the site to update
     * @return mixed redirection after an attemps of updating
     */
    static public function update(Request $request, $siteId) {
        $site = AcnSite::find($siteId);
        $errors = array();
        $nameAlreadyExist = AcnSite::where("SIT_NAME", "=", strtoupper($request->sit_name))->where("SIT_NUM_SITE", "!=", $siteId)->exists();
        if ($nameAlreadyExist) {
            $errors["name"] = "Le nom donné est déjà existant.";
        }
        if (strlen($request->sit_coord) > 127) {
            $errors["coord_len"] = "Les coordonnées ne doivent pas faire plus de 127 caractères.";
        }
        if (strlen($request->sit_description) > 255) {
            $errors["coord_len"] = "La description ne doit pas faire plus de 255 caractères.";
        }
        if (empty($request->sit_name) || empty($request->sit_coord) || empty($request->sit_depth) || empty($request->sit_description)) {
            $errors["empty_entry"] = "Tous les champs doivent êtres remplis.";
        }
        if (count($errors) != 0) return back()->withErrors($errors);
        $site->SIT_NAME = strtoupper($request->sit_name);
        $site->SIT_COORD = $request->sit_coord;
        $site->SIT_DEPTH  = $request->sit_depth;
        $site->SIT_DESCRIPTION  = $request->sit_description;
        $site->save();
        return redirect(route("managerPanel"));
    }

    /**
     * Get the update interface of a site
     *
     * @param number $siteId the identification of the site
     * @return mixed view of the manager's panel page
     */
    static public function getSiteUpdateView($siteId) {
        $site = AcnSite::find($siteId);
        return view("manager/updateSite", ["site" => $site]);
    }
}
