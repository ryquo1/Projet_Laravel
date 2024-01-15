<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcnGroups extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_GROUPS';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'GRP_NUM_GROUPS';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get Dives of the group.
     */
    public function dives()
    {
        return $this->belongsToMany(AcnDives::class, "ACN_REGISTERED", "NUM_GROUPS", "NUM_DIVE");
    }

    /**
     * Get Divers of the group.
     */
    public function divers()
    {
        return $this->belongsToMany(AcnMember::class, "ACN_REGISTERED", "NUM_GROUPS", "NUM_MEMBER");
    }
}
