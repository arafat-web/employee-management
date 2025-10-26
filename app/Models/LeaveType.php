<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'days_allowed',
        'requires_approval',
        'is_paid',
        'color',
        'active',
    ];

    protected $casts = [
        'days_allowed' => 'integer',
        'requires_approval' => 'boolean',
        'is_paid' => 'boolean',
        'active' => 'boolean',
    ];

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
