<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'department_id',
        'description',
        'requirements',
        'expected_employees',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'expected_employees' => 'integer',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function getCurrentEmployeesCountAttribute()
    {
        return $this->employees()->where('status', 'active')->count();
    }
}
