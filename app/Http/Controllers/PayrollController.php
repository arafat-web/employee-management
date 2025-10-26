<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee');

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->latest()->paginate(15);
        $employees = Employee::where('status', 'active')->get();

        return view('payroll.index', compact('payrolls', 'employees'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->with('activeContract')->get();

        return view('payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'tax_deduction' => 'nullable|numeric|min:0',
            'insurance_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Check if payroll already exists
        $exists = Payroll::where('employee_id', $validated['employee_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['month' => 'Payroll already exists for this month.']);
        }

        // Calculate attendance
        $startDate = Carbon::create($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $workingDays = $startDate->diffInDaysFiltered(function (Carbon $date) {
            return $date->isWeekday();
        }, $endDate);

        $attendances = Attendance::where('employee_id', $validated['employee_id'])
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $leaveDays = $attendances->where('status', 'on_leave')->count();

        // Generate payroll number
        $payrollNumber = 'PAY' . $validated['year'] . str_pad($validated['month'], 2, '0', STR_PAD_LEFT) .
                        str_pad($validated['employee_id'], 5, '0', STR_PAD_LEFT);

        $validated['payroll_number'] = $payrollNumber;
        $validated['working_days'] = $workingDays;
        $validated['present_days'] = $presentDays;
        $validated['absent_days'] = $absentDays;
        $validated['leave_days'] = $leaveDays;
        $validated['allowances'] = $validated['allowances'] ?? 0;
        $validated['bonuses'] = $validated['bonuses'] ?? 0;
        $validated['overtime_pay'] = $validated['overtime_pay'] ?? 0;
        $validated['tax_deduction'] = $validated['tax_deduction'] ?? 0;
        $validated['insurance_deduction'] = $validated['insurance_deduction'] ?? 0;
        $validated['other_deductions'] = $validated['other_deductions'] ?? 0;
        $validated['status'] = 'draft';

        $payroll = Payroll::create($validated);
        $payroll->calculateNetSalary();

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'Payroll created successfully!');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee.department');

        return view('payroll.show', compact('payroll'));
    }

    public function process(Payroll $payroll)
    {
        if ($payroll->status !== 'draft') {
            return back()->with('error', 'Only draft payrolls can be processed.');
        }

        $payroll->update(['status' => 'processed']);

        return back()->with('success', 'Payroll processed successfully!');
    }

    public function markAsPaid(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'processed') {
            return back()->with('error', 'Only processed payrolls can be marked as paid.');
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        $payroll->update([
            'status' => 'paid',
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
        ]);

        return back()->with('success', 'Payroll marked as paid!');
    }

    public function generateBulk(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
        ]);

        $employees = Employee::where('status', 'active')->with('activeContract')->get();
        $generated = 0;

        foreach ($employees as $employee) {
            if (!$employee->activeContract) {
                continue;
            }

            $exists = Payroll::where('employee_id', $employee->id)
                ->where('month', $validated['month'])
                ->where('year', $validated['year'])
                ->exists();

            if ($exists) {
                continue;
            }

            // Calculate attendance
            $startDate = Carbon::create($validated['year'], $validated['month'], 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $workingDays = $startDate->diffInDaysFiltered(function (Carbon $date) {
                return $date->isWeekday();
            }, $endDate);

            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $presentDays = $attendances->where('status', 'present')->count();
            $absentDays = $attendances->where('status', 'absent')->count();
            $leaveDays = $attendances->where('status', 'on_leave')->count();

            $payrollNumber = 'PAY' . $validated['year'] . str_pad($validated['month'], 2, '0', STR_PAD_LEFT) .
                            str_pad($employee->id, 5, '0', STR_PAD_LEFT);

            $payroll = Payroll::create([
                'employee_id' => $employee->id,
                'payroll_number' => $payrollNumber,
                'month' => $validated['month'],
                'year' => $validated['year'],
                'basic_salary' => $employee->activeContract->salary,
                'allowances' => 0,
                'bonuses' => 0,
                'overtime_pay' => 0,
                'tax_deduction' => 0,
                'insurance_deduction' => 0,
                'other_deductions' => 0,
                'working_days' => $workingDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'leave_days' => $leaveDays,
                'status' => 'draft',
            ]);

            $payroll->calculateNetSalary();
            $generated++;
        }

        return back()->with('success', "Generated {$generated} payroll records!");
    }
}
