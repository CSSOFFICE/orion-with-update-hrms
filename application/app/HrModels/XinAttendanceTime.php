<?php

namespace App\HrModels;

use Illuminate\Database\Eloquent\Model;

class XinAttendanceTime extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_attendance_time';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'time_attendance_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $guarded = ['time_attendance_id'];
}
