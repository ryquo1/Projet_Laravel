<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\web\AcnBoat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnBoatController extends Controller
{
    /**
     *
     * Get all the boats existing
     * @return mixed view with all the boats in parameter of the view
     */
    static public function getAllBoat() {
        return view ('propose_slot', ["boats" => AcnBoat::all() ]);
    }


    /**
     * Create a boat
     *
     * @param Request $request the request of a boat creation
     * @return mixed redirection to the route of the panel manager
     */
    static public function create(Request $request) {
        $errors = array();
        $nameAlreadyExist = AcnBoat::nameAlreadyExists(strtoupper($request->boa_name));
        if ($nameAlreadyExist) {
            $errors["name"] = "Le nom donné est déjà existant.";
        }
        if ($request->boa_capacity < 4) {
            $errors["number"] = "La capacité doit être supérieure ou égale à 4.";
        }
        if (empty($request->boa_name) || empty($request->boa_capacity)) {
            $errors["empty_entry"] = "Tous les champs doivent êtres remplis.";
        }
        if (count($errors) != 0) return back()->withErrors($errors);
        AcnBoat::createBoat(strtoupper($request->boa_name), $request->boa_capacity);
        return redirect(route("managerPanel"));
    }

    /**
     * Delete a boat
     *
     * @param  $boatId the identification of the boat
     * @return void
     */
    static public function delete($boatNum) {
        AcnBoat::deleteBoat($boatNum);
    }

    /**
     * Update boat's informations
     *
     * @param Request $request the request of a boat's update
     * @param  $boatId the identification of the boat
     * @return mixed
     */
    static public function update(Request $request, $boatNum) {
        $errors = array();
        $nameAlreadyExist = AcnBoat::nameAlreadyExists(strtoupper($request->boa_name), $boatNum);
        if ($nameAlreadyExist) {
            $errors["name"] = "Le nom donné est déjà existant.";
        }
        if ($request->boa_capacity < 4) {
            $errors["number"] = "La capacité doit être supérieure ou égale à 4.";
        }
        if (empty($request->boa_name) || empty($request->boa_capacity)) {
            $errors["empty_entry"] = "Tous les champs doivent êtres remplis.";
        }
        if (count($errors) != 0) return back()->withErrors($errors);
        AcnBoat::updateBoat($boatNum, strtoupper($request->boa_name), $request->boa_capacity);
        return redirect(route("managerPanel"));
    }

    /**
     * Get the view of the updating boat
     *
     * @param $boatId the identification of the boat
     * @return mixed view with the new boats inserted
     */
    static public function getBoatUpdateView($boatId) {
        $boat = AcnBoat::find($boatId);
        return view("manager/updateBoat", ["boat" => $boat]);
    }
}
