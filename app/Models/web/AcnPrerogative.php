<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcnPrerogative extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_PREROGATIVE';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'PRE_NUM_PREROG';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The members that belong to the prerogative.
     */
    public function members()
    {
        return $this->belongsToMany(AcnMember::class, "ACN_RANKED", "NUM_PREROG", "NUM_MEMBER");
    }

    /**
     * return the prerogative
     * @param int $numPre -> the id of the specified prerogative
     * 
     * @return [data_prerogative] -> the prerogative
     */
    public static function getPrerogative($prerogativeNum){
        return AcnPrerogative::find($prerogativeNum);
    }

    /**
     * return the label of a prerogative
     * @param string $numPre -> the id of the specified prerogative
     */
    public static function getPrerogLabel($prePriority){
        return AcnPrerogative::find(AcnPrerogative::getNumPrerog($prePriority))->PRE_LABEL;
    }

    /**
     * return the prioity of a prerogative
     * 
     * @param string $prerogativeNum -> the id of the specified prerogative
     */
    public static function getPriority($prerogativeNum){
        return AcnPrerogative::find($prerogativeNum)->PRE_PRIORITY;
    }

    /**
     * return the level of a prerogative
     * @param int $numPre -> the id of the specified prerogative
     */
    public static function getPrerogLevel($prePriority){
        return AcnPrerogative::find(AcnPrerogative::getNumPrerog($prePriority))->PRE_LEVEL;
    }

    private static function getNumPrerog($prePriority) {
        return DB::table('ACN_PREROGATIVE')
            ->where('PRE_PRIORITY', '=', $prePriority) 
            ->first()
            ->PRE_NUM_PREROG;
    }
    

    public static function getAllPrerogatives() {
        return AcnPrerogative::all();
    }
}

