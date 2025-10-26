<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with(['parent', 'manager'])
            ->withCount('employees');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        $departments = $query->orderBy('name')->paginate(12);

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $departments = Department::where('active', true)->get();
        $employees = Employee::where('status', 'active')->get();

        return view('departments.create', compact('departments', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code',
            'parent_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:employees,id',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active') ? (bool)$request->active : true;

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully!');
    }

    public function show(Department $department)
    {
        $department->load(['parent', 'children', 'manager', 'employees.position']);

        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $departments = Department::where('active', true)
            ->where('id', '!=', $department->id)
            ->get();
        $employees = Employee::where('status', 'active')->get();

        return view('departments.edit', compact('department', 'departments', 'employees'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'parent_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:employees,id',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active') ? (bool)$request->active : $department->active;

        $department->update($validated);

        return redirect()->route('departments.show', $department)
            ->with('success', 'Department updated successfully!');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully!');
    }
}
