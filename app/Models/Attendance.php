<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'worked_hours',
        'overtime_hours',
        'status',
        'notes',
        'check_in_ip',
        'check_out_ip',
    ];

    protected $casts = [
        'date' => 'date',
        'worked_hours' => 'integer',
        'overtime_hours' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateWorkedHours()
    {
        if ($this->check_in && $this->check_out) {
            $checkIn = \Carbon\Carbon::parse($this->check_in);
            $checkOut = \Carbon\Carbon::parse($this->check_out);
            $this->worked_hours = $checkOut->diffInMinutes($checkIn);
            $this->save();
        }
    }

    public function getWorkedHoursFormattedAttribute()
    {
        $hours = floor($this->worked_hours / 60);
        $minutes = $this->worked_hours % 60;
        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
