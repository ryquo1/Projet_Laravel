<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class AcnDives extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_DIVES';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'DIV_NUM_DIVE';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

        /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'DIV_DATE' => 'datetime',
    ];

    /**
     * Get the boat that is used for the diving.
     */
    public function boat(): HasOne
    {
        return $this->hasOne(AcnBoat::class, 'BOA_NUM_BOAT', 'DIV_NUM_BOAT');
    }

    /**
     * Get the site where the dive take place.
     */
    public function site(): HasOne
    {
        return $this->hasOne(AcnSite::class, 'SIT_NUM_SITE', 'DIV_NUM_SITE');
    }

    /**
     * Get the period where the dive take place.
     */
    public function period(): HasOne
    {
        return $this->hasOne(AcnPeriod::class, 'PER_NUM_PERIOD', 'DIV_NUM_PERIOD');
    }

    /**
     * Get the surface security member for the dive.
     */
    public function surfaceSecurity(): HasOne
    {
        return $this->hasOne(AcnMember::class, 'MEM_NUM_MEMBER', 'DIV_NUM_MEMBER_SECURED');
    }

    /**
     * Get the leader for the dive.
     */
    public function leader(): HasOne
    {
        return $this->hasOne(AcnMember::class, 'MEM_NUM_MEMBER', 'DIV_NUM_MEMBER_LEAD');
    }

    /**
     * Get the pilot for the dive.
     */
    public function pilot(): HasOne
    {
        return $this->hasOne(AcnMember::class, 'MEM_NUM_MEMBER', 'DIV_NUM_MEMBER_PILOTING');
    }

    /**
     * Get the minimum prerogative required for the dive.
     */
    public function prerogative(): HasOne
    {
        return $this->hasOne(AcnPrerogative::class, 'PRE_NUM_PREROG', 'DIV_NUM_PREROG');
    }

    /**
     * Get Divers for this dive.
     */
    public function divers()
    {
        return $this->belongsToMany(AcnMember::class, "ACN_REGISTERED", "NUM_DIVE", "NUM_MEMBER");
    }

    /**
     * Get Groups for this dive.
     */
    public function groups()
    {
        return $this->belongsToMany(AcnGroups::class, "ACN_REGISTERED", "NUM_DIVE", "NUM_GROUPS");
    }

    /**
     * returns the Dive
     * @param int $diveId -> the specified dive
     *
     * @return [data_Dives] -> the dive
     */
    public static function getDive($diveId){

        $dive = DB::table('ACN_DIVES')->select('ACN_DIVES.*')->where('DIV_NUM_DIVE','=',$diveId)->get();
        return $dive;
    }

    /**
     * returns all the members that aren't registered and that are eligible for the dive
     * @param int $diveId -> the specified dive
     *
     * @return [list[data_Member]] -> a list of member
     */
    static public function getMembersNotInDive($diveId) {

        $members = AcnDives::find($diveId)->divers;
        $memNums=array();
        foreach($members as $member){
            array_push($memNums, $member['MEM_NUM_MEMBER']);
        }

        $divePriority = AcnDives::getPrerogPriority($diveId);


        $members = DB::table('ACN_MEMBER')
            -> where ('MEM_REMAINING_DIVES', '>', 0)
            -> whereNotIn('MEM_NUM_MEMBER', $memNums)
            -> get();

        $eligibleMembers=array();
        foreach($members as $member) {
            if ($divePriority[0] -> PRE_PRIORITY > 4) {
                if (AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBER) >= $divePriority[0] -> PRE_PRIORITY) {
                    array_push($eligibleMembers, $member);
                }
            } else {
                if ( (AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBER) >= $divePriority[0] -> PRE_PRIORITY)
                && AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBER) <=4
                || AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBER) >= 14) {
                    array_push($eligibleMembers, $member);
                }
            }
        }
        return $eligibleMembers;
    }

    /**
     * retuns the prerogative's priority of the dive's prerogative
     * @param int $diveId -> the specified dive
     *
     * @return [int] -> the perogative's priority
     */
    static public function getPrerogPriority($diveId) {
        return DB::table('ACN_DIVES')
            -> select ('PRE_PRIORITY')
            -> join('ACN_PREROGATIVE', 'ACN_DIVES.DIV_NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
            -> where('DIV_NUM_DIVE', $diveId)
            -> get();
    }

    /**
     * creates a new Dive, the ID (DIV_NUM_DIVE) isn't specified because it is auto-incremented in the database
     * @param [date] $DIV_DATE -> the date of the dive
     * @param int $DIV_NUM_PERIOD -> the id of period of the day for the dive
     * @param int $DIV_NUM_SITE -> the id of the site for the dive
     * @param int $DIV_NUM_BOAT -> the id of boat for the dive
     * @param int $DIV_NUM_PREROG -> the id of the prerogation of the dive
     * @param int $DIV_NUM_MEMBER_LEAD -> the id of the leader of the dive
     * @param int $DIV_NUM_MEMBER_PILOTING -> the id of the pilot of the dive
     * @param int $DIV_NUM_MEMBER_SECURED -> the id of the surface security of the dive
     * @param int $DIV_MIN_REGISTERED -> the minimum of divers required for the dive
     * @param int $DIV_MAX_REGISTERED -> the maximum of divers for the dive
     */
    public static function create($DIV_DATE, $DIV_NUM_PERIOD, $DIV_NUM_SITE, $DIV_NUM_BOAT, $DIV_NUM_PREROG, $DIV_NUM_MEMBER_LEAD, $DIV_NUM_MEMBER_PILOTING, $DIV_NUM_MEMBER_SECURED, $DIV_MIN_REGISTERED, $DIV_MAX_REGISTERED) {
        DB::table('ACN_DIVES')->insert([
            'DIV_DATE' => DB::raw("str_to_date('".$DIV_DATE."','%Y-%m-%d')"),
            'DIV_NUM_PERIOD' => $DIV_NUM_PERIOD,
            'DIV_NUM_SITE' => $DIV_NUM_SITE,
            'DIV_NUM_BOAT' => $DIV_NUM_BOAT,
            'DIV_NUM_PREROG' => $DIV_NUM_PREROG,
            'DIV_NUM_MEMBER_LEAD' => $DIV_NUM_MEMBER_LEAD,
            'DIV_NUM_MEMBER_PILOTING' => $DIV_NUM_MEMBER_PILOTING,
            'DIV_NUM_MEMBER_SECURED'=> $DIV_NUM_MEMBER_SECURED,
            'DIV_MIN_REGISTERED' => $DIV_MIN_REGISTERED,
            'DIV_MAX_REGISTERED'=> $DIV_MAX_REGISTERED,
        ]);
    }

    /**
     * updates the data of the specified dive
     * @param int $diveId -> the id of the modified Dive
     * @param int $DIV_NUM_SITE -> the id of the site for the dive
     * @param int $DIV_NUM_BOAT -> the id of boat for the dive
     * @param int $DIV_NUM_PREROG -> the id of the prerogation of the dive
     * @param int $DIV_NUM_MEMBER_LEAD -> the id of the leader of the dive
     * @param int $DIV_NUM_MEMBER_PILOTING -> the id of the pilot of the dive
     * @param int $DIV_NUM_MEMBER_SECURED -> the id of the surface security of the dive
     * @param int $DIV_MIN_REGISTERED -> the minimum of divers required for the dive
     * @param int $DIV_MAX_REGISTERED -> the maximum of divers for the dive
     */
    public static function updateData($diveId, $DIV_NUM_SITE, $DIV_NUM_BOAT, $DIV_NUM_PREROG, $DIV_NUM_MEMBER_LEAD, $DIV_NUM_MEMBER_PILOTING, $DIV_NUM_MEMBER_SECURED, $DIV_MIN_REGISTERED, $DIV_MAX_REGISTERED){
        DB::table('ACN_DIVES')->where('DIV_NUM_DIVE', '=', $diveId)
            ->update([
                'DIV_NUM_SITE' => $DIV_NUM_SITE,
                'DIV_NUM_BOAT' => $DIV_NUM_BOAT,
                'DIV_NUM_PREROG' => $DIV_NUM_PREROG,
                'DIV_NUM_MEMBER_LEAD' => $DIV_NUM_MEMBER_LEAD,
                'DIV_NUM_MEMBER_PILOTING' => $DIV_NUM_MEMBER_PILOTING,
                'DIV_NUM_MEMBER_SECURED'=> $DIV_NUM_MEMBER_SECURED,
                'DIV_MIN_REGISTERED' => $DIV_MIN_REGISTERED,
                'DIV_MAX_REGISTERED'=> $DIV_MAX_REGISTERED,
            ]);
    }

    /**
     * return all the months during which there is at a least one dive
     *
     * @return [list[month]] -> the months are in date_format
     */
    public static function getMonthWithDive() {
        return DB::table('ACN_DIVES')
            ->selectRaw("DISTINCT date_format(DIV_DATE, '%m') as mois_nb, date_format(DIV_DATE,'%M') as mois_mot")
            ->orderBy('mois_nb')
            ->get();
    }

    /**
     * get all the dives happening in a specified month
     * @param [date] $month -> a date with the month of the dive you want to retrieve, only the month will be retrieved.
     *
     * @return [list[data_Dives]]
     */
    public static function getDivesOfAMonth($month) {
        return DB::table("ACN_DIVES")
            ->join("ACN_PERIOD","PER_NUM_PERIOD","DIV_NUM_PERIOD")
            ->join("ACN_SITE","SIT_NUM_SITE","DIV_NUM_SITE")
            ->join("ACN_PREROGATIVE","PRE_NUM_PREROG","DIV_NUM_PREROG")
            ->whereRaw("date_format(DIV_DATE, '%m') = ?", $month)
            ->get();
    }

    /**
     * return the dives where the specified member is the dive's director
     * @param int $numMember -> the specified user
     *
     * @return [list[data_Dives]] -> the dives
     */
    public static function getDirectorDives($numMember) {
        return DB::table('ACN_DIVES')
            -> where('DIV_NUM_MEMBER_LEAD', $numMember)
            -> where('DIV_DATE', '>=', Carbon::today()->format("Y-m-d"))
            -> orderBy('DIV_DATE')
            -> get();
    }

    public static function getDirectorReport($numMember) {
        return DB::table('ACN_DIVES')
            ->where('DIV_NUM_MEMBER_LEAD', $numMember)
            ->where("DIV_DATE",'<',Carbon::now())
            ->where("DIV_DATE",'>',Carbon::now()->subYear())
            ->orderBy('DIV_DATE', 'desc')
            ->get();
    }
    
    public static function getArchives() {
        return DB::table('ACN_DIVES')
            -> where('DIV_DATE', '<', Carbon::now()->subYear())
            -> orderBy('DIV_DATE')
            -> get();
    }
}
