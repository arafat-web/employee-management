@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Attendance Report -->
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="{{ route('reports.attendance') }}" class="text-decoration-none">
                <div class="table-card text-center hover-shadow" style="cursor: pointer;">
                    <div class="stat-icon mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(113, 75, 103, 0.1); color: #714b67;">
                        <i class="bi bi-calendar-check" style="font-size: 40px;"></i>
                    </div>
                    <h5>Attendance Report</h5>
                    <p class="text-muted mb-0">View attendance records and statistics</p>
                </div>
            </a>
        </div>

        <!-- Leave Report -->
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="{{ route('reports.leave') }}" class="text-decoration-none">
                <div class="table-card text-center hover-shadow" style="cursor: pointer;">
                    <div class="stat-icon mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(0, 160, 157, 0.1); color: #00a09d;">
                        <i class="bi bi-calendar-x" style="font-size: 40px;"></i>
                    </div>
                    <h5>Leave Report</h5>
                    <p class="text-muted mb-0">Analyze leave patterns and usage</p>
                </div>
            </a>
        </div>

        <!-- Payroll Report -->
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="{{ route('reports.payroll') }}" class="text-decoration-none">
                <div class="table-card text-center hover-shadow" style="cursor: pointer;">
                    <div class="stat-icon mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(40, 167, 69, 0.1); color: #28a745;">
                        <i class="bi bi-cash-stack" style="font-size: 40px;"></i>
                    </div>
                    <h5>Payroll Report</h5>
                    <p class="text-muted mb-0">Review payroll summaries and costs</p>
                </div>
            </a>
        </div>

        <!-- Employee Report -->
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="{{ route('reports.employee') }}" class="text-decoration-none">
                <div class="table-card text-center hover-shadow" style="cursor: pointer;">
                    <div class="stat-icon mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                        <i class="bi bi-people" style="font-size: 40px;"></i>
                    </div>
                    <h5>Employee Report</h5>
                    <p class="text-muted mb-0">Employee demographics and statistics</p>
                </div>
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
    }
    .stat-icon {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush
@endsection
