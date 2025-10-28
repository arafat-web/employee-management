<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'check_in_start',
        'check_in_end',
        'check_out_start',
        'check_out_end',
        'work_start_time',
        'work_end_time',
        'late_threshold_minutes',
        'early_leave_threshold_minutes',
        'half_day_hours',
        'full_day_hours',
        'allow_weekend_checkin',
        'require_checkout',
    ];

    protected $casts = [
        'allow_weekend_checkin' => 'boolean',
        'require_checkout' => 'boolean',
    ];

    public static function getSettings()
    {
        return self::first() ?? self::create([]);
    }
}
