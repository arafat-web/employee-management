@extends('layouts.app')

@section('title', 'Employee Details')
@section('page-title', 'Employee Details')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                <li class="breadcrumb-item active">{{ $employee->full_name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="table-card text-center">
                @if($employee->photo)
                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->full_name }}"
                         class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 150px; height: 150px; font-size: 48px;">
                        {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                    </div>
                @endif

                <h4>{{ $employee->full_name }}</h4>
                <p class="text-muted">{{ $employee->employee_code }}</p>
                <span class="badge badge-status-{{ $employee->status }} mb-3">
                    {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                </span>

                <div class="d-grid gap-2">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Employee
                    </a>
                    <a href="{{ route('employees.documents.index', $employee) }}" class="btn btn-info">
                        <i class="bi bi-file-earmark-text"></i> Documents
                    </a>
                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i> Delete Employee
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="table-card">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personal">Personal Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#work">Work Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#attendance">Attendance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#leaves">Leaves</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <div id="personal" class="tab-pane fade show active">
                        <table class="table">
                            <tr>
                                <th width="30%">Email</th>
                                <td>{{ $employee->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $employee->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td>{{ $employee->mobile ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $employee->date_of_birth ? $employee->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{ $employee->gender ? ucfirst($employee->gender) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Marital Status</th>
                                <td>{{ $employee->marital_status ? ucfirst($employee->marital_status) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>
                                    {{ $employee->address ?? '' }}<br>
                                    {{ $employee->city ?? '' }} {{ $employee->state ?? '' }} {{ $employee->zip_code ?? '' }}<br>
                                    {{ $employee->country ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="work" class="tab-pane fade">
                        <table class="table">
                            <tr>
                                <th width="30%">Department</th>
                                <td>{{ $employee->department->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Position</th>
                                <td>{{ $employee->position->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Manager</th>
                                <td>{{ $employee->manager->full_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Joining Date</th>
                                <td>{{ $employee->joining_date ? $employee->joining_date->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Work Email</th>
                                <td>{{ $employee->work_email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Work Location</th>
                                <td>{{ $employee->work_location ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div id="attendance" class="tab-pane fade">
                        <h6>Recent Attendance</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->date->format('M d, Y') }}</td>
                                        <td>{{ $attendance->check_in ?? 'N/A' }}</td>
                                        <td>{{ $attendance->check_out ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : 'danger' }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No attendance records</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="leaves" class="tab-pane fade">
                        <h6>Recent Leave Requests</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->leaveRequests as $leave)
                                    <tr>
                                        <td>{{ $leave->leaveType->name }}</td>
                                        <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                        <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                        <td>{{ $leave->number_of_days }}</td>
                                        <td>
                                            <span class="badge badge-status-{{ $leave->status }}">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No leave requests</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
