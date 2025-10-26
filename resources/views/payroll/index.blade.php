@extends('layouts.app')

@section('title', 'Payroll')
@section('page-title', 'Payroll Management')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Payroll Records</h5>
            <p class="text-muted mb-0">Manage employee salary and payroll</p>
        </div>
        <div>
            <a href="{{ route('payroll.bulk') }}" class="btn btn-success me-2">
                <i class="bi bi-files"></i> Bulk Generate
            </a>
            <a href="{{ route('payroll.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Payroll
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
            <div class="stat-card bg-primary">
                <div class="stat-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">₹{{ number_format($stats['total_gross'], 0) }}</div>
                    <div class="stat-label text-white">Total Gross This Month</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-success">
                <div class="stat-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">₹{{ number_format($stats['total_net'], 0) }}</div>
                    <div class="stat-label text-white">Total Net This Month</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-warning">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">{{ $stats['total_employees'] }}</div>
                    <div class="stat-label text-white">Employees Paid</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-info">
                <div class="stat-icon">
                    <i class="bi bi-calendar-month"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value text-white">{{ now()->format('F Y') }}</div>
                    <div class="stat-label text-white">Current Period</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-card">
                <form method="GET" action="{{ route('payroll.index') }}" class="row g-3 mb-4">
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
                        <label class="form-label">Month</label>
                        <select class="form-select" name="month">
                            <option value="">All Months</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Year</label>
                        <select class="form-select" name="year">
                            @for($y = now()->year; $y >= now()->year - 5; $y--)
                                <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Period</th>
                                <th>Basic Salary</th>
                                <th>Allowances</th>
                                <th>Deductions</th>
                                <th>Gross Salary</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrolls as $payroll)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($payroll->employee->photo)
                                                <img src="{{ asset('storage/' . $payroll->employee->photo) }}"
                                                     alt="{{ $payroll->employee->full_name }}"
                                                     class="rounded-circle me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-2"
                                                     style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ substr($payroll->employee->first_name, 0, 1) }}{{ substr($payroll->employee->last_name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('employees.show', $payroll->employee) }}">
                                                    {{ $payroll->employee->full_name }}
                                                </a>
                                                <br>
                                                <small class="text-muted">{{ $payroll->employee->employee_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }}</strong> {{ $payroll->year }}
                                    </td>
                                    <td>₹{{ number_format($payroll->basic_salary, 2) }}</td>
                                    <td>₹{{ number_format($payroll->allowances, 2) }}</td>
                                    <td>₹{{ number_format($payroll->deductions, 2) }}</td>
                                    <td><strong>₹{{ number_format($payroll->gross_salary, 2) }}</strong></td>
                                    <td><strong class="text-success">₹{{ number_format($payroll->net_salary, 2) }}</strong></td>
                                    <td>
                                        <span class="badge badge-status-{{ $payroll->status }}">
                                            {{ ucfirst(str_replace('_', ' ', $payroll->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('payroll.show', $payroll) }}" class="btn btn-outline-primary" title="View Slip">
                                                <i class="bi bi-file-text"></i>
                                            </a>
                                            @if($payroll->status == 'pending')
                                                <form action="{{ route('payroll.markPaid', $payroll) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success" title="Mark as Paid">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('payroll.destroy', $payroll) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"
                                                        onclick="return confirm('Delete this payroll record?')" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-cash-stack" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-3 mb-0">No payroll records found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payrolls->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $payrolls->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
