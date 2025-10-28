<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function attendance(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $departmentId = $request->input('department');

        $query = Attendance::with(['employee.department'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // Summary statistics
        $summary = [
            'total_days' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'half_day' => $attendances->where('status', 'half_day')->count(),
            'on_leave' => $attendances->where('status', 'on_leave')->count(),
        ];

        $departments = Department::where('active', true)->get();

        return view('reports.attendance', compact('attendances', 'summary', 'departments', 'startDate', 'endDate'));
    }

    public function leave(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $departmentId = $request->input('department');

        $query = LeaveRequest::with(['employee.department', 'leaveType'])
            ->whereYear('start_date', $year);

        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $leaves = $query->orderBy('start_date', 'desc')->get();

        // Summary statistics
        $summary = [
            'total_requests' => $leaves->count(),
            'approved' => $leaves->where('status', 'approved')->count(),
            'pending' => $leaves->where('status', 'pending')->count(),
            'rejected' => $leaves->where('status', 'rejected')->count(),
            'total_days' => $leaves->where('status', 'approved')->sum('number_of_days'),
        ];

        $departments = Department::where('active', true)->get();
        $years = range(date('Y'), date('Y') - 5);

        return view('reports.leave', compact('leaves', 'summary', 'departments', 'years', 'year'));
    }

    public function payroll(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $departmentId = $request->input('department');

        $query = Payroll::with(['employee.department'])
            ->where('month', $month)
            ->where('year', $year);

        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $payrolls = $query->orderBy('employee_id')->get();

        // Summary statistics
        $summary = [
            'total_employees' => $payrolls->count(),
            'total_basic' => $payrolls->sum('basic_salary'),
            'total_allowances' => $payrolls->sum('total_allowances'),
            'total_deductions' => $payrolls->sum('total_deductions'),
            'total_gross' => $payrolls->sum('gross_salary'),
            'total_net' => $payrolls->sum('net_salary'),
            'paid' => $payrolls->where('status', 'paid')->count(),
            'pending' => $payrolls->where('status', 'pending')->count(),
        ];

        $departments = Department::where('active', true)->get();
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $years = range(date('Y'), date('Y') - 5);

        return view('reports.payroll', compact('payrolls', 'summary', 'departments', 'months', 'years', 'month', 'year'));
    }

    public function employee(Request $request)
    {
        $departmentId = $request->input('department');
        $status = $request->input('status', 'active');

        $query = Employee::with(['department', 'position']);

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $employees = $query->orderBy('first_name')->get();

        // Summary statistics
        $summary = [
            'total_employees' => $employees->count(),
            'male' => $employees->where('gender', 'male')->count(),
            'female' => $employees->where('gender', 'female')->count(),
            'avg_salary' => round($employees->avg('salary'), 2),
        ];

        // Group by department
        $byDepartment = $employees->groupBy('department.name')->map(function($dept) {
            return $dept->count();
        });

        $departments = Department::where('active', true)->get();

        return view('reports.employee', compact('employees', 'summary', 'byDepartment', 'departments'));
    }

    public function exportAttendancePdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $departmentId = $request->input('department');

        $query = Attendance::with(['employee.department'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->get();
        $departments = Department::where('active', true)->get();

        $pdf = Pdf::loadView('exports.attendance-pdf', compact('attendances', 'departments'));
        return $pdf->download('attendance-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportLeavePdf(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $departmentId = $request->input('department');

        $query = LeaveRequest::with(['employee.department', 'leaveType'])
            ->whereYear('start_date', $year);

        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $leaves = $query->orderBy('start_date', 'desc')->get();
        $departments = Department::where('active', true)->get();

        $pdf = Pdf::loadView('exports.leave-pdf', compact('leaves', 'departments'));
        return $pdf->download('leave-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportPayrollPdf(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $departmentId = $request->input('department');

        $query = Payroll::with(['employee.department'])
            ->where('month', $month)
            ->where('year', $year);

        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $payrolls = $query->orderBy('employee_id')->get();
        $departments = Department::where('active', true)->get();

        $pdf = Pdf::loadView('exports.payroll-pdf', compact('payrolls', 'departments'));
        return $pdf->download('payroll-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportEmployeePdf(Request $request)
    {
        $departmentId = $request->input('department');
        $status = $request->input('status', 'active');

        $query = Employee::with(['department', 'position']);

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $employees = $query->orderBy('first_name')->get();
        $departments = Department::where('active', true)->get();

        $pdf = Pdf::loadView('exports.employee-pdf', compact('employees', 'departments'));
        return $pdf->download('employee-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
