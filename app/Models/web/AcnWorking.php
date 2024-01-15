<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcnWorking extends Model
{
    /**
     * removes a role from a user
     * @param int $memberNum -> the id of the specified user
     * @param string $roleName -> the name of the role to be removed
     */
    static public function deleteUserRole($memberNum, $roleName) {
        $roleId = AcnFunction::where("FUN_LABEL", $roleName)->first()->FUN_NUM_FUNCTION;
        DB::table("ACN_WORKING")->where("NUM_FUNCTION", $roleId)->where("NUM_MEMBER", $memberNum)->delete();
    }

    /**
     * add a role to a user
     * @param int $memberNum -> the id of the specified user
     * @param string $roleName -> the name of the role to be removed
     */
    static public function createUserRole($memberNum, $roleName) {
        $roleId = AcnFunction::where("FUN_LABEL", $roleName)->first()->FUN_NUM_FUNCTION;
        $isBindingExist = DB::table("ACN_WORKING")->where("NUM_FUNCTION", $roleId)->where("NUM_MEMBER", $memberNum)->exists();
        if ($isBindingExist) return;
        DB::table("ACN_WORKING")->insert(["NUM_FUNCTION" =>$roleId, "NUM_MEMBER" => $memberNum]);
    }
}
                                    