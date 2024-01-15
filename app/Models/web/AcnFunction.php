<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcnFunction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_FUNCTION';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'FUN_NUM_FUNCTION';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The members that belong to the function.
     */
    public function members()
    {
        return $this->belongsToMany(AcnMember::class, "ACN_WORKING", "NUM_FUNCTION", "NUM_MEMBER");
    }
}
