@extends('layouts.app')

@section('title', 'Payroll Report')
@section('page-title', 'Payroll Report')

@section('content')
<div class="container-fluid">
    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="text-center">
                    <h6 class="text-muted mb-1">Employees</h6>
                    <h3 class="mb-0">{{ $summary['total_employees'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="text-center">
                    <h6 class="text-muted mb-1">Total Gross</h6>
                    <h3 class="mb-0">${{ number_format($summary['total_gross'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card danger">
                <div class="text-center">
                    <h6 class="text-muted mb-1">Total Deductions</h6>
                    <h3 class="mb-0">${{ number_format($summary['total_deductions'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="text-center">
                    <h6 class="text-muted mb-1">Total Net</h6>
                    <h3 class="mb-0">${{ number_format($summary['total_net'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            @php
                $monthLabel = (is_int($month) || is_string($month)) ? ($months[$month] ?? '') : '';
            @endphp
            <h5 class="mb-0">Payroll Report - {{ $monthLabel }} {{ $year }}</h5>
            <div>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <a href="{{ route('reports.payroll.pdf', request()->all()) }}" class="btn btn-danger">
                    <i class="bi bi-file-pdf"></i> Export PDF
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Month</label>
                <select name="month" class="form-select">
                    @foreach($months as $m => $name)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Year</label>
                <select name="year" class="form-select">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Basic</th>
                        <th>Allowances</th>
                        <th>Gross</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->employee->full_name }}</td>
                            <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                            <td>${{ number_format($payroll->total_allowances, 2) }}</td>
                            <td>${{ number_format($payroll->gross_salary, 2) }}</td>
                            <td>${{ number_format($payroll->total_deductions, 2) }}</td>
                            <td><strong>${{ number_format($payroll->net_salary, 2) }}</strong></td>
                            <td>
                                <span class="badge {{ $payroll->status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">No payroll records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
