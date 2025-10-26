@extends('layouts.app')

@section('title', 'Employee Report')
@section('page-title', 'Employee Report')

@section('content')
<div class="container-fluid">
    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="text-center">
                    <h6 class="text-muted mb-1">Total Employees</h6>
                    <h3 class="mb-0">{{ $summary['total_employees'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="text-center">
                    <h6 class="text-muted mb-1">Male</h6>
                    <h3 class="mb-0">{{ $summary['male'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card danger">
                <div class="text-center">
                    <h6 class="text-muted mb-1">Female</h6>
                    <h3 class="mb-0">{{ $summary['female'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="text-center">
                    <h6 class="text-muted mb-1">Avg Salary</h6>
                    <h3 class="mb-0">${{ number_format($summary['avg_salary'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Employee Report</h5>
            <div>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Department</label>
                <select name="department" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Gender</th>
                        <th>Salary</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_code }}</td>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->department->name ?? 'N/A' }}</td>
                            <td>{{ $employee->position->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($employee->gender) }}</td>
                            <td>${{ number_format($employee->salary, 2) }}</td>
                            <td>
                                <span class="badge {{ $employee->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
