<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee.department');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date);
            $toDate = Carbon::parse($request->to_date);
            $query->whereBetween('date', [$fromDate, $toDate]);
        } else {
            $query->whereBetween('date', [now()->startOfMonth(), now()]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);

        $departments = \App\Models\Department::where('active', true)->get();

        // Statistics for today
        $stats = [
            'present' => Attendance::where('date', today())->where('status', 'present')->count(),
            'absent' => Attendance::where('date', today())->where('status', 'absent')->count(),
            'late' => Attendance::where('date', today())->where('status', 'late')->count(),
            'on_leave' => Attendance::where('date', today())->where('status', 'on_leave')->count(),
        ];

        return view('attendance.index', compact('attendances', 'departments', 'stats'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->get();

        // Check if current user has already checked in today
        $todayAttendance = null;
        if (auth()->check() && auth()->user()->employee) {
            $todayAttendance = Attendance::where('employee_id', auth()->user()->employee->id)
                ->where('date', today())
                ->first();
        }

        return view('attendance.create', compact('employees', 'todayAttendance'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,half_day,late,on_leave,holiday',
            'notes' => 'nullable|string',
        ]);

        // Check if attendance already exists
        $exists = Attendance::where('employee_id', $validated['employee_id'])
            ->where('date', $validated['date'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'Attendance already marked for this date.']);
        }

        $attendance = Attendance::create($validated);

        if ($validated['check_in'] && $validated['check_out']) {
            $attendance->calculateWorkedHours();
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance marked successfully!');
    }

    public function checkIn(Request $request)
    {
        $employee = auth()->user()->employee;

        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'date' => today(),
            ],
            [
                'check_in' => now()->format('H:i:s'),
                'check_in_ip' => $request->ip(),
                'status' => 'present',
            ]
        );

        if ($attendance->check_in) {
            return back()->with('error', 'Already checked in today!');
        }

        $attendance->update([
            'check_in' => now()->format('H:i:s'),
            'check_in_ip' => $request->ip(),
            'status' => 'present',
        ]);

        return back()->with('success', 'Checked in successfully!');
    }

    public function checkOut(Request $request)
    {
        $employee = auth()->user()->employee;

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', today())
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return back()->with('error', 'Please check in first!');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Already checked out today!');
        }

        $attendance->update([
            'check_out' => now()->format('H:i:s'),
            'check_out_ip' => $request->ip(),
        ]);

        // Calculate work hours
        if ($attendance->check_in) {
            $checkIn = Carbon::createFromFormat('H:i:s', $attendance->check_in);
            $checkOut = Carbon::createFromFormat('H:i:s', $attendance->check_out);
            $attendance->work_hours = $checkOut->diffInHours($checkIn);
            $attendance->save();
        }

        return back()->with('success', 'Checked out successfully!');
    }

    public function checkoutAttendance(Attendance $attendance)
    {
        if ($attendance->check_out) {
            return back()->with('error', 'Already checked out!');
        }

        $attendance->update([
            'check_out' => now()->format('H:i:s'),
        ]);

        // Calculate work hours
        if ($attendance->check_in && $attendance->check_out) {
            $checkIn = Carbon::createFromFormat('H:i:s', $attendance->check_in);
            $checkOut = Carbon::createFromFormat('H:i:s', $attendance->check_out);
            $attendance->work_hours = $checkOut->diffInHours($checkIn);
            $attendance->save();
        }

        return redirect()->route('attendance.index')->with('success', 'Checked out successfully!');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendance.index')->with('success', 'Attendance record deleted successfully!');
    }

    public function report(Request $request)
    {
        $month = $request->filled('month') ? $request->month : now()->month;
        $year = $request->filled('year') ? $request->year : now()->year;

        $employees = Employee::where('status', 'active')
            ->with(['attendances' => function ($query) use ($month, $year) {
                $query->byMonth($year, $month);
            }])
            ->get();

        return view('attendance.report', compact('employees', 'month', 'year'));
    }
}
