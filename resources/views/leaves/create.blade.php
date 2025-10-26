@extends('layouts.app')

@section('title', 'New Leave Request')
@section('page-title', 'New Leave Request')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leave Requests</a></li>
                <li class="breadcrumb-item active">New Request</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="table-card">
                <form action="{{ route('leaves.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select class="form-select @error('employee_id') is-invalid @enderror"
                                    id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} ({{ $employee->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="leave_type_id" class="form-label">Leave Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('leave_type_id') is-invalid @enderror"
                                    id="leave_type_id" name="leave_type_id" required>
                                <option value="">Select Leave Type</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}"
                                            data-days="{{ $type->default_days }}"
                                            {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->default_days }} days)
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Duration:</strong> <span id="duration-display">0 days</span>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('reason') is-invalid @enderror"
                                      id="reason" name="reason" rows="4" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Submit Request
                        </button>
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="table-card">
                <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Leave Balance</h6>
                <div id="leave-balance-info">
                    <p class="text-muted">Select an employee to view their leave balance</p>
                </div>
            </div>

            <div class="table-card mt-3">
                <h6 class="mb-3"><i class="bi bi-list-ul me-2"></i>Leave Types</h6>
                <div class="list-group list-group-flush">
                    @foreach($leaveTypes as $type)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge" style="background-color: {{ $type->color }}"></span>
                                {{ $type->name }}
                            </div>
                            <span class="badge bg-secondary">{{ $type->default_days }} days</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Calculate duration when dates change
    document.getElementById('start_date').addEventListener('change', calculateDuration);
    document.getElementById('end_date').addEventListener('change', calculateDuration);

    function calculateDuration() {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);

        if (startDate && endDate && endDate >= startDate) {
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            document.getElementById('duration-display').textContent = diffDays + ' days';
        } else {
            document.getElementById('duration-display').textContent = '0 days';
        }
    }

    // Load leave balance when employee changes
    document.getElementById('employee_id').addEventListener('change', function() {
        const employeeId = this.value;
        const leaveBalanceInfo = document.getElementById('leave-balance-info');

        if (!employeeId) {
            leaveBalanceInfo.innerHTML = '<p class="text-muted">Select an employee to view their leave balance</p>';
            return;
        }

        leaveBalanceInfo.innerHTML = '<div class="spinner-border spinner-border-sm"></div> Loading...';

        fetch(`/leaves/balance/${employeeId}`)
            .then(response => response.json())
            .then(data => {
                let html = '<table class="table table-sm">';
                data.forEach(balance => {
                    html += `
                        <tr>
                            <td>${balance.leave_type}</td>
                            <td class="text-end">
                                <span class="badge bg-success">${balance.available}</span> /
                                ${balance.total}
                            </td>
                        </tr>
                    `;
                });
                html += '</table>';
                leaveBalanceInfo.innerHTML = html;
            })
            .catch(error => {
                leaveBalanceInfo.innerHTML = '<p class="text-danger">Error loading balance</p>';
            });
    });
</script>
@endpush
