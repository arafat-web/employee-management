@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Company Settings -->
        <div class="col-md-6 col-lg-4 mb-4">
            <a href="{{ route('settings.company') }}" class="text-decoration-none">
                <div class="table-card text-center hover-shadow" style="cursor: pointer;">
                    <div class="stat-icon mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(113, 75, 103, 0.1); color: #714b67;">
                        <i class="bi bi-building" style="font-size: 40px;"></i>
                    </div>
                    <h5>Company Settings</h5>
                    <p class="text-muted mb-0">Manage company information</p>
                </div>
            </a>
        </div>

        <!-- Leave Types -->
        <div class="col-md-6 col-lg-4 mb-4">
            <a href="{{ route('settings.leave-types') }}" class="text-decoration-none">
                <div class="table-card text-center hover-shadow" style="cursor: pointer;">
                    <div class="stat-icon mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(0, 160, 157, 0.1); color: #00a09d;">
                        <i class="bi bi-calendar-range" style="font-size: 40px;"></i>
                    </div>
                    <h5>Leave Types</h5>
                    <p class="text-muted mb-0">Configure leave types and policies</p>
                </div>
            </a>
        </div>

        <!-- Positions -->
        <div class="col-md-6 col-lg-4 mb-4">
            <a href="{{ route('settings.positions') }}" class="text-decoration-none">
                <div class="table-card text-center hover-shadow" style="cursor: pointer;">
                    <div class="stat-icon mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(40, 167, 69, 0.1); color: #28a745;">
                        <i class="bi bi-briefcase" style="font-size: 40px;"></i>
                    </div>
                    <h5>Positions</h5>
                    <p class="text-muted mb-0">Manage job positions and roles</p>
                </div>
            </a>
        </div>

        <!-- Holidays -->
        <div class="col-md-6 col-lg-4 mb-4">
            <a href="{{ route('settings.holidays') }}" class="text-decoration-none">
                <div class="table-card text-center hover-shadow" style="cursor: pointer;">
                    <div class="stat-icon mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                        <i class="bi bi-calendar-event" style="font-size: 40px;"></i>
                    </div>
                    <h5>Holidays</h5>
                    <p class="text-muted mb-0">Configure public and company holidays</p>
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
