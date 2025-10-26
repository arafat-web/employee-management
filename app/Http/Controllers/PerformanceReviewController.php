<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\Employee;
use Illuminate\Http\Request;

class PerformanceReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = PerformanceReview::with(['employee', 'reviewer']);

        // Search by employee
        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        // Filter by period
        if ($request->filled('period')) {
            $query->where('review_period', $request->period);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('overall_rating', $request->rating);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('review_date', $request->year);
        }

        $reviews = $query->orderBy('review_date', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total_reviews' => PerformanceReview::count(),
            'average_rating' => round(PerformanceReview::avg('overall_rating'), 1),
            'excellent_count' => PerformanceReview::where('overall_rating', '>=', 4.5)->count(),
            'needs_improvement' => PerformanceReview::where('overall_rating', '<', 3)->count(),
        ];

        $employees = Employee::where('status', 'active')->get();
        $years = range(date('Y'), date('Y') - 5);

        return view('performance.index', compact('reviews', 'stats', 'employees', 'years'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        $reviewers = Employee::where('status', 'active')->get();

        return view('performance.create', compact('employees', 'reviewers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'reviewer_id' => 'required|exists:employees,id',
            'review_date' => 'required|date',
            'review_period' => 'required|in:Q1,Q2,Q3,Q4,Yearly,Mid-Year',
            'quality_of_work' => 'required|numeric|min:1|max:5',
            'productivity' => 'required|numeric|min:1|max:5',
            'communication' => 'required|numeric|min:1|max:5',
            'teamwork' => 'required|numeric|min:1|max:5',
            'initiative' => 'required|numeric|min:1|max:5',
            'attendance_punctuality' => 'required|numeric|min:1|max:5',
            'overall_rating' => 'required|numeric|min:1|max:5',
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'goals' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        PerformanceReview::create($validated);

        return redirect()->route('performance.index')
            ->with('success', 'Performance review created successfully.');
    }

    public function show(PerformanceReview $performance)
    {
        $performance->load(['employee.department', 'employee.position', 'reviewer']);

        // Get previous reviews for comparison
        $previousReviews = PerformanceReview::where('employee_id', $performance->employee_id)
            ->where('id', '!=', $performance->id)
            ->orderBy('review_date', 'desc')
            ->limit(3)
            ->get();

        return view('performance.show', compact('performance', 'previousReviews'));
    }

    public function edit(PerformanceReview $performance)
    {
        $employees = Employee::where('status', 'active')->get();
        $reviewers = Employee::where('status', 'active')->get();

        return view('performance.edit', compact('performance', 'employees', 'reviewers'));
    }

    public function update(Request $request, PerformanceReview $performance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'reviewer_id' => 'required|exists:employees,id',
            'review_date' => 'required|date',
            'review_period' => 'required|in:Q1,Q2,Q3,Q4,Yearly,Mid-Year',
            'quality_of_work' => 'required|numeric|min:1|max:5',
            'productivity' => 'required|numeric|min:1|max:5',
            'communication' => 'required|numeric|min:1|max:5',
            'teamwork' => 'required|numeric|min:1|max:5',
            'initiative' => 'required|numeric|min:1|max:5',
            'attendance_punctuality' => 'required|numeric|min:1|max:5',
            'overall_rating' => 'required|numeric|min:1|max:5',
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'goals' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        $performance->update($validated);

        return redirect()->route('performance.show', $performance)
            ->with('success', 'Performance review updated successfully.');
    }

    public function destroy(PerformanceReview $performance)
    {
        $performance->delete();

        return redirect()->route('performance.index')
            ->with('success', 'Performance review deleted successfully.');
    }
}
