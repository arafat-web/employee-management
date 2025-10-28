@extends('layouts.app')

@section('title', 'Attendance Report')
@section('page-title', 'Attendance Report')

@section('content')
<div class="container-fluid">
    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="stat-card primary">
                <div class="text-center">
                    <div class="stat-icon mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total Days</h6>
                    <h3 class="mb-0">{{ $summary['total_days'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card success">
                <div class="text-center">
                    <div class="stat-icon mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h6 class="text-muted mb-1">Present</h6>
                    <h3 class="mb-0">{{ $summary['present'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card danger">
                <div class="text-center">
                    <div class="stat-icon mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <h6 class="text-muted mb-1">Absent</h6>
                    <h3 class="mb-0">{{ $summary['absent'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card warning">
                <div class="text-center">
                    <div class="stat-icon mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-clock"></i>
                    </div>
                    <h6 class="text-muted mb-1">Late</h6>
                    <h3 class="mb-0">{{ $summary['late'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card info">
                <div class="text-center">
                    <div class="stat-icon mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-slash-circle"></i>
                    </div>
                    <h6 class="text-muted mb-1">Half Day</h6>
                    <h3 class="mb-0">{{ $summary['half_day'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card secondary">
                <div class="text-center">
                    <div class="stat-icon mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h6 class="text-muted mb-1">On Leave</h6>
                    <h3 class="mb-0">{{ $summary['on_leave'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Report Table -->
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Attendance Report</h5>
            <div>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Reports
                </a>
                <a href="{{ route('reports.attendance.pdf', request()->all()) }}" class="btn btn-danger">
                    <i class="bi bi-file-pdf"></i> Export PDF
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
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
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </form>

        <!-- Attendance Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Work Hours</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                            <td>{{ $attendance->employee->full_name }}</td>
                            <td>{{ $attendance->employee->department->name ?? 'N/A' }}</td>
                            <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : 'N/A' }}</td>
                            <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : 'N/A' }}</td>
                            <td>{{ $attendance->work_hours ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $badgeClass = match($attendance->status) {
                                        'present' => 'bg-success',
                                        'absent' => 'bg-danger',
                                        'late' => 'bg-warning',
                                        'half_day' => 'bg-info',
                                        'on_leave' => 'bg-secondary',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucwords(str_replace('_', ' ', $attendance->status)) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .sidebar, .top-navbar, .btn, form { display: none !important; }
        .main-content { margin-left: 0 !important; }
        .table-card { box-shadow: none !important; }
    }
</style>
@endpush
@endsection
