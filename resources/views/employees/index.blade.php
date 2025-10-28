@extends('layouts.app')

@section('title', 'Employees')
@section('page-title', 'Employees')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Employees</li>
                </ol>
            </nav>
        </div>
        <div>
            @if(auth()->user()->hasPermission('create_employees'))
            <div class="btn-group me-2" role="group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-upload"></i> Import
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('employees.import.view') }}">
                        <i class="bi bi-upload"></i> Import from Excel/CSV
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('employees.template') }}">
                        <i class="bi bi-download"></i> Download Template
                    </a></li>
                </ul>
            </div>
            @endif

            @if(auth()->user()->hasPermission('view_employees'))
            <div class="btn-group me-2" role="group">
                <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('employees.export', array_merge(request()->all(), ['format' => 'xlsx'])) }}">
                        <i class="bi bi-file-excel"></i> Export to Excel
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('employees.export', array_merge(request()->all(), ['format' => 'csv'])) }}">
                        <i class="bi bi-filetype-csv"></i> Export to CSV
                    </a></li>
                </ul>
            </div>
            @endif

            @if(auth()->user()->hasPermission('create_employees'))
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Employee
            </a>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="table-card mb-3">
        <form method="GET" action="{{ route('employees.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by name, code, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="department" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="group_by" class="form-select">
                    <option value="">No Grouping</option>
                    <option value="department" {{ request('group_by') == 'department' ? 'selected' : '' }}>Group by Department</option>
                    <option value="status" {{ request('group_by') == 'status' ? 'selected' : '' }}>Group by Status</option>
                    <option value="position" {{ request('group_by') == 'position' ? 'selected' : '' }}>Group by Position</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Employees Table -->
    <div class="table-card">
        @if($groupedEmployees)
            @foreach($groupedEmployees as $groupName => $groupItems)
                <div class="mb-4">
                    <h5 class="bg-light p-3 rounded">
                        <i class="bi bi-folder"></i> {{ $groupName ?: 'Ungrouped' }}
                        <span class="badge bg-primary">{{ $groupItems->count() }}</span>
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    @if(request('group_by') != 'department')
                                    <th>Department</th>
                                    @endif
                                    @if(request('group_by') != 'position')
                                    <th>Position</th>
                                    @endif
                                    @if(request('group_by') != 'status')
                                    <th>Status</th>
                                    @endif
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupItems as $employee)
                                    <tr>
                                        <td>{{ $employee->employee_code }}</td>
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}">
                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                            </a>
                                        </td>
                                        <td>{{ $employee->email }}</td>
                                        @if(request('group_by') != 'department')
                                        <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                        @endif
                                        @if(request('group_by') != 'position')
                                        <td>{{ $employee->position->title ?? 'N/A' }}</td>
                                        @endif
                                        @if(request('group_by') != 'status')
                                        <td>
                                            @if($employee->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($employee->status == 'inactive')
                                                <span class="badge bg-secondary">Inactive</span>
                                            @elseif($employee->status == 'on_leave')
                                                <span class="badge bg-warning">On Leave</span>
                                            @else
                                                <span class="badge bg-danger">Terminated</span>
                                            @endif
                                        </td>
                                        @endif
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(auth()->user()->hasPermission('edit_employees'))
                                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_code }}</td>
                            <td>
                                <a href="{{ route('employees.show', $employee) }}">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </a>
                            </td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->department->name ?? 'N/A' }}</td>
                            <td>{{ $employee->position->title ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-status-{{ $employee->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->hasPermission('edit_employees'))
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                    @if(auth()->user()->hasPermission('delete_employees'))
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No employees found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($employees)
        <div class="mt-3">
            {{ $employees->links() }}
        </div>
        @endif
        @endif
    </div>
@endsection
