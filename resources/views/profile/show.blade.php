@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="table-card text-center">
                @if($user->photo)
                    <img src="{{ asset('uploads/profiles/' . $user->photo) }}" alt="Profile Photo" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; background: #714b67; color: white; font-size: 60px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                
                <h4>{{ $user->name }}</h4>
                @if($user->role)
                    <span class="badge bg-primary mb-3">{{ $user->role->display_name }}</span>
                @endif
                <p class="text-muted">{{ $user->email }}</p>

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </a>
                    <a href="{{ route('profile.password.edit') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-lock"></i> Change Password
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="table-card">
                <h5 class="mb-4">Profile Information</h5>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Full Name:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $user->name }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Email:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $user->email }}
                    </div>
                </div>

                @if($user->phone)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Phone:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $user->phone }}
                    </div>
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Role:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($user->role)
                            <span class="badge bg-primary">{{ $user->role->display_name }}</span>
                        @else
                            <span class="badge bg-secondary">No Role Assigned</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Member Since:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $user->created_at->format('F d, Y') }}
                    </div>
                </div>

                @if($employee)
                <hr class="my-4">
                <h5 class="mb-4">Employee Information</h5>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Employee Code:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $employee->employee_code }}
                    </div>
                </div>

                @if($employee->department)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Department:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $employee->department->name }}
                    </div>
                </div>
                @endif

                @if($employee->position)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Position:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $employee->position->name }}
                    </div>
                </div>
                @endif

                @if($employee->joining_date)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Joining Date:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $employee->joining_date->format('F d, Y') }}
                    </div>
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($employee->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($employee->status === 'on_leave')
                            <span class="badge bg-warning">On Leave</span>
                        @elseif($employee->status === 'resigned')
                            <span class="badge bg-danger">Resigned</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($employee->status) }}</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
