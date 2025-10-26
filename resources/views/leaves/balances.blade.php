@extends('layouts.app')

@section('title', 'Leave Balances')
@section('page-title', 'Leave Balances')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Employee Leave Balances</h5>
            <p class="text-muted mb-0">View and manage leave balances for all employees</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="table-card">
                <form method="GET" action="{{ route('leaves.balances') }}" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search"
                               placeholder="Search by name or employee code..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="department">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="leave_type">
                            <option value="">All Leave Types</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ request('leave_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                @foreach($leaveTypes as $type)
                                    <th class="text-center" title="{{ $type->name }}">
                                        <span class="badge" style="background-color: {{ $type->color }}">
                                            {{ $type->code ?? substr($type->name, 0, 2) }}
                                        </span>
                                    </th>
                                @endforeach
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($employee->photo)
                                                <img src="{{ asset('storage/' . $employee->photo) }}"
                                                     alt="{{ $employee->full_name }}"
                                                     class="rounded-circle me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-2"
                                                     style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('employees.show', $employee) }}">
                                                    {{ $employee->full_name }}
                                                </a>
                                                <br>
                                                <small class="text-muted">{{ $employee->employee_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                    @php
                                        $totalAvailable = 0;
                                        $totalDays = 0;
                                    @endphp
                                    @foreach($leaveTypes as $type)
                                        @php
                                            $balance = $employee->leaveBalances->firstWhere('leave_type_id', $type->id);
                                            $available = $balance ? $balance->available_days : $type->default_days;
                                            $total = $balance ? $balance->total_days : $type->default_days;
                                            $totalAvailable += $available;
                                            $totalDays += $total;
                                        @endphp
                                        <td class="text-center">
                                            <span class="badge {{ $available > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $available }}
                                            </span>
                                            <small class="text-muted">/ {{ $total }}</small>
                                        </td>
                                    @endforeach
                                    <td class="text-center">
                                        <strong>{{ $totalAvailable }}</strong> / {{ $totalDays }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 3 + $leaveTypes->count() }}" class="text-center py-4">
                                        <i class="bi bi-people" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-3 mb-0">No employees found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($employees->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $employees->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-card">
                <h5 class="mb-3">Leave Type Legend</h5>
                <div class="row">
                    @foreach($leaveTypes as $type)
                        <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge" style="background-color: {{ $type->color }}; min-width: 60px;">
                                    {{ $type->code ?? substr($type->name, 0, 2) }}
                                </span>
                                <div class="ms-3">
                                    <div class="fw-bold">{{ $type->name }}</div>
                                    <small class="text-muted">{{ $type->default_days }} days/year</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
