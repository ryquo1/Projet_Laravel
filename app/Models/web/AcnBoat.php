<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcnBoat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_BOAT';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'BOA_NUM_BOAT';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $hidden = ["BOA_DELETED"];

    /**
     * return the specified Boat
     * @param int $num_boat
     *
     * @return [data_Boat]
     */
    public static function getBoat($num_boat){
        $boat = DB::table('ACN_BOAT')
        -> select('BOA_NAME')
        -> where('BOA_NUM_BOAT','=',$num_boat)
        ->get();
        return $boat;
    }

    /**
     * return all the boats that aren't deleted
     *
     * @return [list[data_Boat]] -> a list of boat
     */
    static public function getAllBoats() {
        return AcnBoat::where("BOA_DELETED", "=", 0)->get();
    }

    /**
     * Get the capacity of a boat
     * 
     * @param $BoatNum the number from the specific boat
     * @return int of the capacity of the boat
     */
    static public function getBoatCapacity($boatNum) {
        return AcnBoat::find($boatNum)->BOA_CAPACITY;
    }

    /**
     * Get the capacity of divers of a boat
     * 
     * @param $BoatNum if not specified return the capacity of the biggest boat
     * @return int of the capacity of the boat
     */
    static public function getBoatDiversCapacity($boatNum=null) {
        if (!is_null($boatNum)){
            return AcnBoat::find($boatNum)->BOA_CAPACITY-3;
        }
        return AcnBoat::all()->max('BOA_CAPACITY');
    }

    /**
     * Get the max capacity of all the boat
     * 
     * @return int the max capacity of all the boat
     */
    public static function getMaxCapacity() {
        return AcnBoat::all()->max('BOA_CAPACITY');
    }

    /**
     * Get the boat's name
     * 
     * @param int $BoatNum num from the specific boat
     * @return string the name of the boat
     */
    static public function getBoatName($boatNum) {
        return AcnBoat::find($boatNum)->BOA_NAME;
    }

    /**
     * creates a boat
     * 
     * @param string $boatName -> the new boat's name
     * @param int $boatCapacity -> the new boat's capacity
     */
    static public function createBoat($boatName, $boatCapacity) {
        $boat = new AcnBoat;
        $boat->BOA_NAME = $boatName;
        $boat->BOA_CAPACITY = $boatCapacity;
        $boat->save();
    }

    /**
     * deletes a boat
     * 
     * @param int $boatNum -> the boat's id
     */
    public static function deleteBoat($boatNum) {
        $boat = AcnBoat::find($boatNum);
        $boat->BOA_DELETED = 1;
        $boat->save();
    }

    /**
     * creates a boat
     * 
     * @param string $boatName -> the new boat's name
     * @param int $boatNum -> (optional) to be specified in case of an update
     */
    public static function nameAlreadyExists($boatName, $boatNum=null) {
        if (is_null($boatNum)) {
            return AcnBoat::where("BOA_NAME", "=", $boatName)
                ->exists();
        }
        return AcnBoat::where("BOA_NAME", "=", $boatName)
            ->where("BOA_NUM_BOAT", "!=", $boatNum)
            ->exists();
    }

    public static function updateBoat($boatNum, $boatName, $boatCapacity) {
        
        $boat = AcnBoat::find($boatNum);
        $boat->BOA_NAME = strtoupper($boatName);
        $boat->BOA_CAPACITY = $boatCapacity;
        $boat->save();
    }
}
