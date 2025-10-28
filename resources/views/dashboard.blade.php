@extends('layouts.app')

@section('title', 'Dashboard - Employee Management System')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Employee Check-in/Check-out Section (Only for employees) -->
    @if(auth()->user()->employee)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h5 class="mb-2"><i class="bi bi-clock"></i> Today's Attendance</h5>
                                <p class="text-muted mb-0">{{ now()->format('l, F d, Y') }}</p>
                            </div>
                            <div class="col-md-4 text-center">
                                @php
                                    $todayAttendanceRecord = \App\Models\Attendance::where('employee_id', auth()->user()->employee->id)
                                        ->where('date', today())
                                        ->first();
                                @endphp

                                @if($todayAttendanceRecord)
                                    <div class="attendance-time">
                                        <div class="mb-2">
                                            <strong>Check-in:</strong> <span class="text-success">{{ $todayAttendanceRecord->check_in ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <strong>Check-out:</strong>
                                            <span class="text-{{ $todayAttendanceRecord->check_out ? 'danger' : 'muted' }}">
                                                {{ $todayAttendanceRecord->check_out ?? 'Not checked out' }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="bi bi-clock-history fs-2"></i>
                                        <p class="mb-0">No attendance recorded today</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4 text-end">
                                @if(!$todayAttendanceRecord || !$todayAttendanceRecord->check_in)
                                    <form action="{{ route('attendance.employee.checkIn') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="bi bi-box-arrow-in-right"></i> Check In
                                        </button>
                                    </form>
                                @elseif(!$todayAttendanceRecord->check_out)
                                    <form action="{{ route('attendance.employee.checkOut') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-lg">
                                            <i class="bi bi-box-arrow-right"></i> Check Out
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-success mb-0">
                                        <i class="bi bi-check-circle"></i> Attendance completed for today
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $totalEmployees }}</h3>
                        <p class="text-muted mb-0">Total Employees</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $todayAttendance }}</h3>
                        <p class="text-muted mb-0">Present Today</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $pendingLeaves }}</h3>
                        <p class="text-muted mb-0">Pending Leaves</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $totalDepartments }}</h3>
                        <p class="text-muted mb-0">Departments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Recent Employees -->
        <div class="col-md-6">
            <div class="table-card">
                <h5 class="mb-3">Recent Employees</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEmployees as $employee)
                                <tr>
                                    <td>
                                        <a href="{{ route('employees.show', $employee) }}">
                                            {{ $employee->full_name }}
                                        </a>
                                    </td>
                                    <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                    <td>{{ $employee->position->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-status-{{ $employee->status }}">
                                            {{ ucfirst($employee->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No employees found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Upcoming Birthdays -->
        <div class="col-md-6">
            <div class="table-card">
                <h5 class="mb-3">Upcoming Birthdays</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingBirthdays as $employee)
                                <tr>
                                    <td>{{ $employee->full_name }}</td>
                                    <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                    <td>
                                        <i class="bi bi-gift"></i>
                                        {{ $employee->date_of_birth->format('M d') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No upcoming birthdays</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Department Statistics -->
        <div class="col-md-6">
            <div class="table-card">
                <h5 class="mb-3">Department Statistics</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Employees</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departmentStats as $dept)
                                <tr>
                                    <td>
                                        <span style="display: inline-block; width: 12px; height: 12px; border-radius: 50%; background: {{ $dept->color }};"></span>
                                        {{ $dept->name }}
                                    </td>
                                    <td>{{ $dept->employees_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">No departments found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Announcements -->
        <div class="col-md-6">
            <div class="table-card">
                <h5 class="mb-3">Announcements</h5>
                <div class="list-group list-group-flush">
                    @forelse($announcements as $announcement)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    @if($announcement->is_pinned)
                                        <i class="bi bi-pin-fill text-danger"></i>
                                    @endif
                                    {{ $announcement->title }}
                                </h6>
                                <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ Str::limit($announcement->content, 100) }}</p>
                            <small class="badge bg-{{ $announcement->priority === 'high' ? 'danger' : ($announcement->priority === 'medium' ? 'warning' : 'info') }}">
                                {{ ucfirst($announcement->priority) }}
                            </small>
                        </div>
                    @empty
                        <div class="list-group-item text-center">
                            No announcements
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Attendance Trend Chart -->
        <div class="col-md-12">
            <div class="table-card">
                <h5 class="mb-3">Attendance Trend (Last 6 Months)</h5>
                <canvas id="attendanceChart" height="80"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Attendance Trend Chart
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($attendanceTrend, 'month')) !!},
            datasets: [{
                label: 'Present Days',
                data: {!! json_encode(array_column($attendanceTrend, 'count')) !!},
                borderColor: 'rgb(113, 75, 103)',
                backgroundColor: 'rgba(113, 75, 103, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
