@extends('layouts.app')

@section('title', 'Leave Request Details')
@section('page-title', 'Leave Request Details')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leave Requests</a></li>
                <li class="breadcrumb-item active">Details</li>
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
        <div class="col-md-8 mb-4">
            <div class="table-card">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="mb-2">{{ $leaveRequest->leaveType->name }}</h4>
                        <span class="badge badge-status-{{ $leaveRequest->status }} fs-6">
                            {{ ucfirst($leaveRequest->status) }}
                        </span>
                    </div>
                    <div class="text-end">
                        @if($leaveRequest->status == 'pending')
                            <form action="{{ route('leaves.approve', $leaveRequest) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('leaves.reject', $leaveRequest) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle"></i> Reject
                                </button>
                            </form>
                        @endif
                        @if($leaveRequest->status == 'pending' || $leaveRequest->status == 'approved')
                            <form action="{{ route('leaves.cancel', $leaveRequest) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Cancel this leave request?')">
                                    <i class="bi bi-slash-circle"></i> Cancel
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Leave Duration</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box me-3" style="background-color: #28a74520; color: #28a745;">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Start Date</div>
                                <div class="fw-bold">{{ $leaveRequest->start_date->format('l, F d, Y') }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box me-3" style="background-color: #dc354520; color: #dc3545;">
                                <i class="bi bi-calendar-x"></i>
                            </div>
                            <div>
                                <div class="text-muted small">End Date</div>
                                <div class="fw-bold">{{ $leaveRequest->end_date->format('l, F d, Y') }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="icon-box me-3" style="background-color: #007bff20; color: #007bff;">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Duration</div>
                                <div class="fw-bold">{{ $leaveRequest->number_of_days }} Days</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Request Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Applied On:</th>
                                <td>{{ $leaveRequest->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @if($leaveRequest->approved_by)
                                <tr>
                                    <th>Approved By:</th>
                                    <td>{{ $leaveRequest->approver->full_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Approved On:</th>
                                    <td>{{ $leaveRequest->approved_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                                </tr>
                            @endif
                            @if($leaveRequest->status == 'rejected' && $leaveRequest->rejection_reason)
                                <tr>
                                    <th>Rejection Reason:</th>
                                    <td><span class="text-danger">{{ $leaveRequest->rejection_reason }}</span></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <hr>

                <h6 class="text-muted mb-3">Reason for Leave</h6>
                <div class="bg-light p-3 rounded">
                    {{ $leaveRequest->reason }}
                </div>

                @if($leaveRequest->notes)
                    <hr>
                    <h6 class="text-muted mb-3">Additional Notes</h6>
                    <div class="bg-light p-3 rounded">
                        {{ $leaveRequest->notes }}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="table-card text-center mb-3">
                @if($leaveRequest->employee->photo)
                    <img src="{{ asset('storage/' . $leaveRequest->employee->photo) }}"
                         alt="{{ $leaveRequest->employee->full_name }}"
                         class="rounded-circle mb-3"
                         style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 100px; height: 100px; font-size: 36px;">
                        {{ substr($leaveRequest->employee->first_name, 0, 1) }}{{ substr($leaveRequest->employee->last_name, 0, 1) }}
                    </div>
                @endif

                <h5>{{ $leaveRequest->employee->full_name }}</h5>
                <p class="text-muted mb-2">{{ $leaveRequest->employee->employee_code }}</p>
                <p class="text-muted mb-3">{{ $leaveRequest->employee->department->name ?? 'N/A' }}</p>

                <a href="{{ route('employees.show', $leaveRequest->employee) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-person"></i> View Profile
                </a>
            </div>

            <div class="table-card">
                <h6 class="mb-3"><i class="bi bi-calendar-check me-2"></i>Leave Balance</h6>
                @if($leaveRequest->employee->leaveBalances->count() > 0)
                    <table class="table table-sm">
                        @foreach($leaveRequest->employee->leaveBalances as $balance)
                            <tr>
                                <td>
                                    <span class="badge" style="background-color: {{ $balance->leaveType->color }}">
                                        {{ $balance->leaveType->name }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <strong>{{ $balance->available_days }}</strong> / {{ $balance->total_days }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p class="text-muted text-center">No leave balance information</p>
                @endif
            </div>

            <div class="table-card mt-3">
                <h6 class="mb-3"><i class="bi bi-clock-history me-2"></i>Recent Leaves</h6>
                <div class="list-group list-group-flush">
                    @foreach($leaveRequest->employee->leaveRequests()->where('id', '!=', $leaveRequest->id)->latest()->take(5)->get() as $recent)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <small>{{ $recent->leaveType->name }}</small>
                                <span class="badge badge-status-{{ $recent->status }}">{{ ucfirst($recent->status) }}</span>
                            </div>
                            <small class="text-muted">
                                {{ $recent->start_date->format('M d') }} - {{ $recent->end_date->format('M d, Y') }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
