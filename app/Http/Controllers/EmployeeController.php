<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Exports\EmployeesExport;
use App\Imports\EmployeesImport;
use Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Check if user has permission to view employees
        if (!auth()->user()->hasPermission('view_employees')) {
            abort(403, 'Unauthorized action.');
        }

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

        // Group By functionality
        $groupBy = $request->input('group_by');
        $groupedEmployees = null;

        if ($groupBy && in_array($groupBy, ['department', 'status', 'position'])) {
            $allEmployees = $query->get();

            if ($groupBy === 'department') {
                $groupedEmployees = $allEmployees->groupBy('department.name');
            } elseif ($groupBy === 'status') {
                $groupedEmployees = $allEmployees->groupBy('status');
            } elseif ($groupBy === 'position') {
                $groupedEmployees = $allEmployees->groupBy('position.title');
            }

            $employees = null;
        } else {
            $employees = $query->paginate(15);
        }

        $departments = Department::where('active', true)->get();
        $positions = Position::where('active', true)->get();

        return view('employees.index', compact('employees', 'departments', 'positions', 'groupedEmployees', 'groupBy'));
    }

    public function create()
    {
        // Check if user has permission to create employees
        if (!auth()->user()->hasPermission('create_employees')) {
            abort(403, 'Unauthorized action.');
        }

        $departments = Department::where('active', true)->get();
        $positions = Position::where('active', true)->get();
        $managers = Employee::where('status', 'active')->get();

        return view('employees.create', compact('departments', 'positions', 'managers'));
    }

    public function store(Request $request)
    {
        // Check if user has permission to create employees
        if (!auth()->user()->hasPermission('create_employees')) {
            abort(403, 'Unauthorized action.');
        }

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
        // Check if user has permission to edit employees
        if (!auth()->user()->hasPermission('edit_employees')) {
            abort(403, 'Unauthorized action.');
        }

        $departments = Department::where('active', true)->get();
        $positions = Position::where('active', true)->get();
        $managers = Employee::where('status', 'active')
            ->where('id', '!=', $employee->id)
            ->get();

        return view('employees.edit', compact('employee', 'departments', 'positions', 'managers'));
    }

    public function update(Request $request, Employee $employee)
    {
        // Check if user has permission to edit employees
        if (!auth()->user()->hasPermission('edit_employees')) {
            abort(403, 'Unauthorized action.');
        }

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
        // Check if user has permission to delete employees
        if (!auth()->user()->hasPermission('delete_employees')) {
            abort(403, 'Unauthorized action.');
        }

        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully!');
    }

    public function export(Request $request)
    {
        // Check if user has permission to view employees
        if (!auth()->user()->hasPermission('view_employees')) {
            abort(403, 'Unauthorized action.');
        }
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

        $format = $request->input('format', 'xlsx');

        if ($format === 'csv') {
            return Excel::create('employees-' . now()->format('Y-m-d'), function($excel) use ($query) {
                $excel->sheet('Employees', function($sheet) use ($query) {
                    $sheet->fromArray((new EmployeesExport($query))->collection()->toArray());
                });
            })->download('csv');
        }

        return Excel::create('employees-' . now()->format('Y-m-d'), function($excel) use ($query) {
            $excel->sheet('Employees', function($sheet) use ($query) {
                $sheet->fromArray((new EmployeesExport($query))->collection()->toArray());
            });
        })->download('xlsx');
    }

    public function importView()
    {
        // Check if user has permission to create employees
        if (!auth()->user()->hasPermission('create_employees')) {
            abort(403, 'Unauthorized action.');
        }

        return view('employees.import');
    }

    public function import(Request $request)
    {
        // Check if user has permission to create employees
        if (!auth()->user()->hasPermission('create_employees')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::load($request->file('file'), function($reader) {
                foreach ($reader->get() as $row) {
                    $import = new EmployeesImport();
                    $import->model($row->toArray());
                }
            });

            return redirect()->route('employees.index')
                ->with('success', 'Employees imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing employees: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'employee_code',
            'first_name',
            'last_name',
            'email',
            'phone',
            'address',
            'city',
            'state',
            'zip_code',
            'date_of_birth',
            'gender',
            'marital_status',
            'nationality',
            'national_id',
            'department',
            'position',
            'join_date',
            'employment_type',
            'salary',
            'status',
            'emergency_contact',
            'emergency_phone',
            'bank_name',
            'bank_account',
            'password',
        ];

        return Excel::create('employee-import-template', function($excel) use ($headers) {
            $excel->sheet('Template', function($sheet) use ($headers) {
                $sheet->fromArray([$headers], null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
