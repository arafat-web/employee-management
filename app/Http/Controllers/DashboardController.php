<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::where('status', 'active')->count();
        $totalDepartments = Department::where('active', true)->count();

        // Today's attendance
        $todayAttendance = Attendance::whereDate('date', today())
            ->where('status', 'present')
            ->count();

        // Pending leave requests
        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();

        // Recent employees
        $recentEmployees = Employee::with(['department', 'position'])
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        // Upcoming birthdays
        $upcomingBirthdays = Employee::whereMonth('date_of_birth', now()->month)
            ->whereDay('date_of_birth', '>=', now()->day)
            ->where('status', 'active')
            ->orderByRaw('DAY(date_of_birth)')
            ->take(5)
            ->get();

        // Active announcements
        $announcements = Announcement::active()
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Department wise employee count
        $departmentStats = Department::withCount(['employees' => function ($query) {
            $query->where('status', 'active');
        }])
            ->where('active', true)
            ->orderBy('employees_count', 'desc')
            ->take(5)
            ->get();

        // Monthly attendance trend (last 6 months)
        $attendanceTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $attendanceTrend[] = [
                'month' => $month->format('M'),
                'count' => Attendance::whereYear('date', $month->year)
                    ->whereMonth('date', $month->month)
                    ->where('status', 'present')
                    ->count()
            ];
        }

        return view('dashboard', compact(
            'totalEmployees',
            'totalDepartments',
            'todayAttendance',
            'pendingLeaves',
            'recentEmployees',
            'upcomingBirthdays',
            'announcements',
            'departmentStats',
            'attendanceTrend'
        ));
    }
}
