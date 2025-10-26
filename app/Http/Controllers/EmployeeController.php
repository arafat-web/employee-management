<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position', 'manager']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->paginate(15);
        $departments = Department::where('active', true)->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('active', true)->get();
        $positions = Position::where('active', true)->get();
        $managers = Employee::where('status', 'active')->get();

        return view('employees.create', compact('departments', 'positions', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'manager_id' => 'nullable|exists:employees,id',
            'joining_date' => 'nullable|date',
            'work_location' => 'nullable|string|max:255',
            'work_email' => 'nullable|email',
            'work_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'), // Default password
        ]);

        // Generate employee code
        $latestEmployee = Employee::latest('id')->first();
        $employeeCode = 'EMP' . str_pad(($latestEmployee ? $latestEmployee->id + 1 : 1), 5, '0', STR_PAD_LEFT);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('employee_photos', 'public');
        }

        $validated['user_id'] = $user->id;
        $validated['employee_code'] = $employeeCode;
        $validated['photo'] = $photoPath;
        $validated['status'] = 'active';

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully!');
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'position',
            'manager',
            'subordinates',
            'contracts',
            'attendances' => function ($query) {
                $query->latest()->take(10);
            },
            'leaveRequests' => function ($query) {
                $query->latest()->take(5);
            },
            'skills',
            'performanceReviews' => function ($query) {
                $query->latest()->take(5);
            },
            'documents'
        ]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::where('active', true)->get();
        $positions = Position::where('active', true)->get();
        $managers = Employee::where('status', 'active')
            ->where('id', '!=', $employee->id)
            ->get();

        return view('employees.edit', compact('employee', 'departments', 'positions', 'managers'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'manager_id' => 'nullable|exists:employees,id',
            'joining_date' => 'nullable|date',
            'work_location' => 'nullable|string|max:255',
            'work_email' => 'nullable|email',
            'work_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,on_leave,terminated',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employee_photos', 'public');
        }

        $employee->update($validated);

        // Update user
        $employee->user->update([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully!');
    }
}
