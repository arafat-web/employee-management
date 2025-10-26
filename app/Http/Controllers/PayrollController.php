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
        $query = Payroll::with('employee.department');

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

        // Statistics
        $currentMonth = $request->filled('month') ? $request->month : now()->month;
        $currentYear = $request->filled('year') ? $request->year : now()->year;

        $stats = [
            'total_gross' => Payroll::where('month', $currentMonth)
                ->where('year', $currentYear)
                ->sum('gross_salary'),
            'total_net' => Payroll::where('month', $currentMonth)
                ->where('year', $currentYear)
                ->sum('net_salary'),
            'total_employees' => Payroll::where('month', $currentMonth)
                ->where('year', $currentYear)
                ->count(),
        ];

        return view('payroll.index', compact('payrolls', 'employees', 'stats'));
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
            'working_days' => 'nullable|integer',
            'hra' => 'nullable|numeric|min:0',
            'da' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'pf' => 'nullable|numeric|min:0',
            'esi' => 'nullable|numeric|min:0',
            'tds' => 'nullable|numeric|min:0',
            'loan' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
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

        // Calculate totals
        $allowances = ($validated['hra'] ?? 0) + ($validated['da'] ?? 0) +
                     ($validated['medical_allowance'] ?? 0) + ($validated['transport_allowance'] ?? 0) +
                     ($validated['other_allowance'] ?? 0);

        $deductions = ($validated['pf'] ?? 0) + ($validated['esi'] ?? 0) +
                     ($validated['tds'] ?? 0) + ($validated['loan'] ?? 0) +
                     ($validated['other_deduction'] ?? 0);

        $grossSalary = $validated['basic_salary'] + $allowances;
        $netSalary = $grossSalary - $deductions;

        $validated['allowances'] = $allowances;
        $validated['deductions'] = $deductions;
        $validated['gross_salary'] = $grossSalary;
        $validated['net_salary'] = $netSalary;
        $validated['status'] = 'pending';

        Payroll::create($validated);

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll generated successfully!');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee.department', 'employee.position');

        return view('payroll.show', compact('payroll'));
    }

    public function bulk()
    {
        $employees = Employee::where('status', 'active')
            ->with('department', 'position')
            ->get();
        $departments = \App\Models\Department::where('active', true)->get();

        return view('payroll.bulk', compact('employees', 'departments'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
        ]);

        $generated = 0;
        $skipped = 0;

        foreach ($validated['employee_ids'] as $employeeId) {
            // Check if already exists
            $exists = Payroll::where('employee_id', $employeeId)
                ->where('month', $validated['month'])
                ->where('year', $validated['year'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            $employee = Employee::find($employeeId);

            // Calculate totals (using default values)
            $basicSalary = $employee->basic_salary ?? 0;
            $allowances = $basicSalary * 0.2; // 20% of basic as allowances
            $deductions = $basicSalary * 0.12; // 12% for PF, ESI, etc.
            $grossSalary = $basicSalary + $allowances;
            $netSalary = $grossSalary - $deductions;

            Payroll::create([
                'employee_id' => $employeeId,
                'month' => $validated['month'],
                'year' => $validated['year'],
                'basic_salary' => $basicSalary,
                'allowances' => $allowances,
                'deductions' => $deductions,
                'gross_salary' => $grossSalary,
                'net_salary' => $netSalary,
                'working_days' => 26,
                'status' => 'pending',
            ]);

            $generated++;
        }

        $message = "Generated {$generated} payroll records.";
        if ($skipped > 0) {
            $message .= " Skipped {$skipped} existing records.";
        }

        return redirect()->route('payroll.index')->with('success', $message);
    }

    public function markPaid(Payroll $payroll)
    {
        $payroll->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        return back()->with('success', 'Payroll marked as paid!');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payroll.index')->with('success', 'Payroll record deleted successfully!');
    }
}
