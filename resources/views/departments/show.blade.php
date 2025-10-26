@extends('layouts.app')

@section('title', 'Department Details')
@section('page-title', $department->name)

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
                <li class="breadcrumb-item active">{{ $department->name }}</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="table-card">
                <div class="icon-box mb-3" style="background-color: {{ $department->color }}20; color: {{ $department->color }}">
                    <i class="bi bi-building"></i>
                </div>

                <h4>{{ $department->name }}</h4>
                <p class="text-muted mb-3">{{ $department->code }}</p>

                <span class="badge badge-status-{{ $department->active ? 'active' : 'inactive' }} mb-3">
                    {{ $department->active ? 'Active' : 'Inactive' }}
                </span>

                @if($department->description)
                    <p class="text-muted small">{{ $department->description }}</p>
                @endif

                <div class="mt-4 d-grid gap-2">
                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Department
                    </a>
                    <form action="{{ route('departments.destroy', $department) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Are you sure? This will affect {{ $department->employees->count() }} employees.')">
                            <i class="bi bi-trash"></i> Delete Department
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-card mt-4">
                <h6 class="mb-3">Department Information</h6>
                <table class="table table-sm">
                    <tr>
                        <th width="40%">Manager:</th>
                        <td>{{ $department->manager->full_name ?? 'Not assigned' }}</td>
                    </tr>
                    <tr>
                        <th>Employees:</th>
                        <td>{{ $department->employees->count() }}</td>
                    </tr>
                    @if($department->parent)
                        <tr>
                            <th>Parent Dept:</th>
                            <td>
                                <a href="{{ route('departments.show', $department->parent) }}">
                                    {{ $department->parent->name }}
                                </a>
                            </td>
                        </tr>
                    @endif
                    @if($department->children->count() > 0)
                        <tr>
                            <th>Sub-departments:</th>
                            <td>{{ $department->children->count() }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            @if($department->children->count() > 0)
                <div class="table-card mb-4">
                    <h5 class="mb-3">Sub-departments ({{ $department->children->count() }})</h5>
                    <div class="row">
                        @foreach($department->children as $child)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-box-sm me-3" style="background-color: {{ $child->color }}20; color: {{ $child->color }}">
                                                <i class="bi bi-building"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">
                                                    <a href="{{ route('departments.show', $child) }}">{{ $child->name }}</a>
                                                </h6>
                                                <small class="text-muted">{{ $child->employees->count() }} employees</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="table-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Department Employees ({{ $department->employees->count() }})</h5>
                    <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Employee
                    </a>
                </div>

                @if($department->employees->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($department->employees as $employee)
                                    <tr>
                                        <td>
                                            @if($employee->photo)
                                                <img src="{{ asset('storage/' . $employee->photo) }}"
                                                     alt="{{ $employee->full_name }}"
                                                     class="rounded-circle"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px; font-size: 16px;">
                                                    {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}">
                                                {{ $employee->full_name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $employee->employee_code }}</small>
                                        </td>
                                        <td>{{ $employee->position->name ?? 'N/A' }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>
                                            <span class="badge badge-status-{{ $employee->status }}">
                                                {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('employees.show', $employee) }}"
                                                   class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('employees.edit', $employee) }}"
                                                   class="btn btn-outline-secondary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-people" style="font-size: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-3 mb-0">No employees in this department</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .icon-box-sm {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
</style>
@endpush
