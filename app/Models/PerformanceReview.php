<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'review_date',
        'review_period',
        'overall_rating',
        'technical_skills_rating',
        'communication_rating',
        'teamwork_rating',
        'productivity_rating',
        'punctuality_rating',
        'strengths',
        'areas_of_improvement',
        'goals',
        'comments',
        'employee_comments',
        'status',
        'acknowledged_at',
    ];

    protected $casts = [
        'review_date' => 'date',
        'overall_rating' => 'integer',
        'technical_skills_rating' => 'integer',
        'communication_rating' => 'integer',
        'teamwork_rating' => 'integer',
        'productivity_rating' => 'integer',
        'punctuality_rating' => 'integer',
        'acknowledged_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function calculateAverageRating()
    {
        $ratings = array_filter([
            $this->technical_skills_rating,
            $this->communication_rating,
            $this->teamwork_rating,
            $this->productivity_rating,
            $this->punctuality_rating,
        ]);

        return count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 2) : 0;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
