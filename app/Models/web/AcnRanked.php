<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcnRanked extends Model
{
    /**
     * returns all the prerogatives of the user except for 'E1'
     * @param int $memberNum -> the id of the specified member
     * @param int $member_prerogative -> the id of the max prerogative of the user
     * 
     * @return [list[data_Prerogative]] -> all the prerogatives of the user except 'E1'
     */
    static public function getAllPRevPrerogativeNotE1($memberNum,$member_prerogative){
        return DB::table('ACN_RANKED')
                ->select('NUM_PREROG')->distinct()
                ->where('NUM_PREROG', '<=' , $member_prerogative)
                ->where('NUM_PREROG', '>' , 4)
                ->whereNotIn('NUM_PREROG',DB::table('ACN_RANKED')
                ->select('NUM_PREROG')
                ->where('NUM_MEMBER', '=', $memberNum))
                ->get();
    }

    /**
     * return all prerogative for members who have the 'E1' prerogative
     * @param int $memberNum
     * @param int $member_prerogative
     * 
     * @return [list[data_Prerogative]] -> all the user's prerogative without the 'E1'
     */
    static public function getAllPRevPrerogativeButE1($memberNum,$member_prerogative){
        return DB::table('ACN_RANKED')
        ->select('NUM_PREROG')->distinct()
        ->where('NUM_PREROG', '<=' , 8)
        ->where('NUM_PREROG', '>' , 4)
        ->whereNotIn('NUM_PREROG',DB::table('ACN_RANKED')
        ->select('NUM_PREROG')
        ->where('NUM_MEMBER', '=', $memberNum))
        ->get();
    }

    /**
     * returns all the prerogatives of the user if he is a child
     * @param int $memberNum -> the id of the specified member
     * @param int $member_prerogative -> the id of the max prerogative of the user
     * 
     * @return [list[data_Prerogative]] -> all the prerogatives of the user except
     */
    static public function getAllPRevPrerogativeChildren($memberNum,$member_prerogative){
        return DB::table('ACN_RANKED')
        ->select('NUM_PREROG')->distinct()
        ->where('NUM_PREROG', '<=' , $member_prerogative)
        ->where('NUM_PREROG', '<' , 4)
        ->whereNotIn('NUM_PREROG',DB::table('ACN_RANKED')
        ->select('NUM_PREROG')
        ->where('NUM_MEMBER', '=', $memberNum))
        ->get();
    }

    /**
     * insert all the prerogatives for a user
     * @param [list[data_Prerogative]] $pre -> all the prerogatives to be added to the user
     * @param int $memberNum -> the id of the specified user
     */
    static public function insertAllPrerogative($pre,$memberNum){
        foreach($pre as $prerogative){
            DB::table('ACN_RANKED')->where('NUM_MEMBER','=',$memberNum)->insert(['NUM_MEMBER'=>$memberNum,'NUM_PREROG'=>$prerogative->NUM_PREROG]);
        }
    }
}
                                    