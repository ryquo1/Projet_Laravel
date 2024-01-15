<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcnRegistered extends Model
{
    /**
     * insert data in to add a member to a dive
     * @param int $numMember -> the id of the member
     * @param int $numDive -> the id of the dive
     */
    static public function insert($numMember, $numDive) {
        DB::table('ACN_REGISTERED')->insert([
            'NUM_DIVE' => $numDive,
            'NUM_MEMBER' => $numMember,
        ]);
    }

    /**
     * delete data to remove a member from a dive
     * @param int $numMember -> the id of the member
     * @param int $numDive -> the id of the dive
     */
    static public function deleteData($numMember, $numDive) {
        DB::table('ACN_REGISTERED')
            ->where ('NUM_MEMBER', $numMember)
            ->where ('NUM_DIVE', $numDive)
            ->delete();
    }

    /**
     * delete data to remove a dive
     * @param int $numDive -> the id of the dive
     */
    static public function deleteDive($numDive) {
        DB::table('ACN_REGISTERED')
            ->where ('NUM_DIVE', $numDive)
            ->delete();
    }
}
                                    