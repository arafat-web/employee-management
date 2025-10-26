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
        $query = LeaveRequest::with(['employee', 'leaveType', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type_id', $request->leave_type);
        }

        $leaveRequests = $query->latest()->paginate(15);
        $employees = Employee::where('status', 'active')->get();
        $leaveTypes = LeaveType::where('active', true)->get();

        return view('leaves.index', compact('leaveRequests', 'employees', 'leaveTypes'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::where('active', true)->get();
        $employee = auth()->user()->employee;
        $leaveBalances = LeaveBalance::where('employee_id', $employee->id)
            ->where('year', now()->year)
            ->with('leaveType')
            ->get();

        return view('leaves.create', compact('leaveTypes', 'leaveBalances'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $employee = auth()->user()->employee;

        // Calculate number of days
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $numberOfDays = $startDate->diffInDays($endDate) + 1;

        // Check leave balance
        $leaveBalance = LeaveBalance::where('employee_id', $employee->id)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('year', now()->year)
            ->first();

        if (!$leaveBalance || $leaveBalance->remaining_days < $numberOfDays) {
            return back()->withErrors(['leave_type_id' => 'Insufficient leave balance.']);
        }

        // Handle attachment upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave_attachments', 'public');
        }

        $validated['employee_id'] = $employee->id;
        $validated['number_of_days'] = $numberOfDays;
        $validated['attachment'] = $attachmentPath;
        $validated['status'] = 'pending';

        LeaveRequest::create($validated);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request submitted successfully!');
    }

    public function show(LeaveRequest $leave)
    {
        $leave->load(['employee', 'leaveType', 'approvedBy']);

        return view('leaves.show', compact('leave'));
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

    public function reject(Request $request, LeaveRequest $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave request has already been processed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $leave->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Leave request rejected.');
    }

    public function balances()
    {
        $employee = auth()->user()->employee;
        $leaveBalances = LeaveBalance::where('employee_id', $employee->id)
            ->where('year', now()->year)
            ->with('leaveType')
            ->get();

        return view('leaves.balances', compact('leaveBalances'));
    }
}
