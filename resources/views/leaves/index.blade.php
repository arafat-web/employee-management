@extends('layouts.app')

@section('title', 'Leave Requests')
@section('page-title', 'Leave Management')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Leave Requests</h5>
            <p class="text-muted mb-0">Manage employee leave requests and balances</p>
        </div>
        <div>
            <a href="{{ route('leaves.calendar') }}" class="btn btn-info">
                <i class="bi bi-calendar3"></i> Calendar View
            </a>
            <a href="{{ route('leaves.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Leave Request
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-warning">
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">{{ $stats['pending'] }}</div>
                    <div class="stat-label text-white">Pending Approval</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-success">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">{{ $stats['approved'] }}</div>
                    <div class="stat-label text-white">Approved</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-danger">
                <div class="stat-icon">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">{{ $stats['rejected'] }}</div>
                    <div class="stat-label text-white">Rejected</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-info">
                <div class="stat-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">{{ $stats['total_days'] }}</div>
                    <div class="stat-label text-white">Total Days This Month</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-card">
                <form method="GET" action="{{ route('leaves.index') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Employee</label>
                        <select class="form-select" name="employee">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Leave Type</label>
                        <select class="form-select" name="leave_type">
                            <option value="">All Types</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ request('leave_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Duration</th>
                                <th>Days</th>
                                <th>Applied Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveRequests as $leave)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($leave->employee->photo)
                                                <img src="{{ asset('storage/' . $leave->employee->photo) }}"
                                                     alt="{{ $leave->employee->full_name }}"
                                                     class="rounded-circle me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-2"
                                                     style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ substr($leave->employee->first_name, 0, 1) }}{{ substr($leave->employee->last_name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('employees.show', $leave->employee) }}">
                                                    {{ $leave->employee->full_name }}
                                                </a>
                                                <br>
                                                <small class="text-muted">{{ $leave->employee->department->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $leave->leaveType->color ?? '#6c757d' }}">
                                            {{ $leave->leaveType->name }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $leave->start_date->format('M d, Y') }}
                                        <i class="bi bi-arrow-right"></i>
                                        {{ $leave->end_date->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <strong>{{ $leave->number_of_days }}</strong> days
                                    </td>
                                    <td>{{ $leave->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-status-{{ $leave->status }}">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('leaves.show', $leave) }}" class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($leave->status == 'pending')
                                                <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success" title="Approve">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger" title="Reject">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($leave->status == 'pending' || $leave->status == 'approved')
                                                <form action="{{ route('leaves.cancel', $leave) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-warning"
                                                            onclick="return confirm('Cancel this leave request?')" title="Cancel">
                                                        <i class="bi bi-slash-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-calendar-x" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-3 mb-0">No leave requests found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($leaveRequests->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
