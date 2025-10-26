<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_skills')
            ->withPivot('proficiency_level', 'acquired_date', 'notes')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
