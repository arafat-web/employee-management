<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee.department', 'leaveType', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type_id', $request->leave_type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('end_date', '<=', $request->to_date);
        }

        $leaveRequests = $query->latest()->paginate(15);
        $employees = Employee::where('status', 'active')->get();
        $leaveTypes = LeaveType::where('active', true)->get();

        // Statistics
        $stats = [
            'pending' => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
            'total_days' => LeaveRequest::where('status', 'approved')
                ->whereMonth('start_date', now()->month)
                ->sum('number_of_days'),
        ];

        return view('leaves.index', compact('leaveRequests', 'employees', 'leaveTypes', 'stats'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::where('active', true)->get();
        $employees = Employee::where('status', 'active')->get();

        return view('leaves.create', compact('leaveTypes', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        // Calculate number of days
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $numberOfDays = $startDate->diffInDays($endDate) + 1;

        // Check leave balance
        $leaveBalance = LeaveBalance::where('employee_id', $validated['employee_id'])
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('year', now()->year)
            ->first();

        if ($leaveBalance && $leaveBalance->available_days < $numberOfDays) {
            return back()->withErrors(['leave_type_id' => 'Insufficient leave balance.']);
        }

        // Handle attachment upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave_attachments', 'public');
        }

        $validated['number_of_days'] = $numberOfDays;
        $validated['attachment'] = $attachmentPath;
        $validated['status'] = 'pending';

        LeaveRequest::create($validated);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request submitted successfully!');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee.department', 'employee.leaveBalances.leaveType', 'leaveType', 'approver']);

        return view('leaves.show', compact('leaveRequest'));
    }

    public function approve(LeaveRequest $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave request has already been processed.');
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Update leave balance
        $leaveBalance = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', now()->year)
            ->first();

        if ($leaveBalance) {
            $leaveBalance->used_days += $leave->number_of_days;
            $leaveBalance->updateBalance();
        }

        return back()->with('success', 'Leave request approved successfully!');
    }

    public function reject(LeaveRequest $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave request has already been processed.');
        }

        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->user()->employee->id ?? null,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Leave request rejected.');
    }

    public function cancel(LeaveRequest $leave)
    {
        if (!in_array($leave->status, ['pending', 'approved'])) {
            return back()->with('error', 'Cannot cancel this leave request.');
        }

        // If approved, restore leave balance
        if ($leave->status === 'approved') {
            $leaveBalance = LeaveBalance::where('employee_id', $leave->employee_id)
                ->where('leave_type_id', $leave->leave_type_id)
                ->where('year', now()->year)
                ->first();

            if ($leaveBalance) {
                $leaveBalance->used_days -= $leave->number_of_days;
                $leaveBalance->available_days += $leave->number_of_days;
                $leaveBalance->save();
            }
        }

        $leave->update(['status' => 'cancelled']);

        return back()->with('success', 'Leave request cancelled successfully!');
    }

    public function balances(Request $request)
    {
        $query = Employee::where('status', 'active')
            ->with(['department', 'leaveBalances.leaveType']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        $employees = $query->paginate(20);
        $departments = \App\Models\Department::where('active', true)->get();
        $leaveTypes = LeaveType::where('active', true)->get();

        return view('leaves.balances', compact('employees', 'departments', 'leaveTypes'));
    }

    public function getBalance($employeeId)
    {
        $balances = LeaveBalance::where('employee_id', $employeeId)
            ->where('year', now()->year)
            ->with('leaveType')
            ->get();

        return response()->json($balances->map(function ($balance) {
            return [
                'leave_type' => $balance->leaveType->name,
                'total' => $balance->total_days,
                'used' => $balance->used_days,
                'available' => $balance->available_days,
            ];
        }));
    }

    public function calendar()
    {
        return view('leaves.calendar');
    }

    public function calendarData(Request $request)
    {
        $leaves = LeaveRequest::with(['employee', 'leaveType'])
            ->get();

        $events = $leaves->map(function ($leave) {
            $color = match($leave->status) {
                'approved' => '#28a745',
                'pending' => '#ffc107',
                'rejected' => '#dc3545',
                default => '#6c757d',
            };

            return [
                'id' => $leave->id,
                'title' => $leave->employee->first_name . ' ' . $leave->employee->last_name . ' - ' . $leave->leaveType->name,
                'start' => $leave->start_date,
                'end' => date('Y-m-d', strtotime($leave->end_date . ' +1 day')),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'status' => ucfirst($leave->status),
                    'employee' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                    'leave_type' => $leave->leaveType->name,
                    'days' => $leave->days,
                ],
            ];
        });

        return response()->json($events);
    }
}
