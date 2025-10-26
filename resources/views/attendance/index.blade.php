@extends('layouts.app')

@section('title', 'Attendance')
@section('page-title', 'Attendance Management')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Attendance Records</h5>
            <p class="text-muted mb-0">Track and manage employee attendance</p>
        </div>
        <a href="{{ route('attendance.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Mark Attendance
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-success">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">{{ $stats['present'] }}</div>
                    <div class="stat-label text-white">Present Today</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-danger">
                <div class="stat-icon">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">{{ $stats['absent'] }}</div>
                    <div class="stat-label text-white">Absent Today</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-warning">
                <div class="stat-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">{{ $stats['late'] }}</div>
                    <div class="stat-label text-white">Late Today</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-info">
                <div class="stat-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">{{ $stats['on_leave'] }}</div>
                    <div class="stat-label text-white">On Leave</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-card">
                <form method="GET" action="{{ route('attendance.index') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" name="from_date"
                               value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" name="to_date"
                               value="{{ request('to_date', now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Department</label>
                        <select class="form-select" name="department">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Work Hours</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($attendance->employee->photo)
                                                <img src="{{ asset('storage/' . $attendance->employee->photo) }}"
                                                     alt="{{ $attendance->employee->full_name }}"
                                                     class="rounded-circle me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-2"
                                                     style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ substr($attendance->employee->first_name, 0, 1) }}{{ substr($attendance->employee->last_name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('employees.show', $attendance->employee) }}">
                                                    {{ $attendance->employee->full_name }}
                                                </a>
                                                <br>
                                                <small class="text-muted">{{ $attendance->employee->employee_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $attendance->employee->department->name ?? 'N/A' }}</td>
                                    <td>{{ $attendance->date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="{{ $attendance->is_late ? 'text-danger' : 'text-success' }}">
                                            {{ $attendance->check_in ?? 'N/A' }}
                                            @if($attendance->is_late)
                                                <i class="bi bi-exclamation-circle" title="Late arrival"></i>
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $attendance->check_out ?? 'Not yet' }}</td>
                                    <td>
                                        @if($attendance->work_hours)
                                            {{ number_format($attendance->work_hours, 2) }} hrs
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : ($attendance->status == 'late' ? 'warning' : 'info')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if(!$attendance->check_out && $attendance->status == 'present')
                                                <form action="{{ route('attendance.checkout', $attendance) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-primary" title="Check Out">
                                                        <i class="bi bi-box-arrow-right"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"
                                                        onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-calendar-x" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-3 mb-0">No attendance records found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendances->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $attendances->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
