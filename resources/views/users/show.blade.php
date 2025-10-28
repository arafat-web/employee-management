@extends('layouts.app')

@section('title', 'View User')
@section('page-title', 'User Details')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person"></i> User Information</h5>
                    @if(auth()->user()->canManageRoles())
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-light btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" class="rounded-circle" width="120" height="120" alt="{{ $user->name }}">
                            @else
                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 48px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <h4 class="mt-3 mb-0">{{ $user->name }}</h4>
                            @if($user->role)
                                <span class="badge bg-primary fs-6">{{ $user->role->display_name }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Email</label>
                                <p class="mb-0"><i class="bi bi-envelope"></i> {{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Phone</label>
                                <p class="mb-0"><i class="bi bi-telephone"></i> {{ $user->phone ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Role</label>
                                <p class="mb-0">
                                    @if($user->role)
                                        <span class="badge bg-primary">{{ $user->role->display_name }}</span>
                                    @else
                                        <span class="badge bg-secondary">No Role Assigned</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Employee Record</label>
                                <p class="mb-0">
                                    @if($user->employee)
                                        <a href="{{ route('employees.show', $user->employee) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-person-badge"></i> View Employee ({{ $user->employee->employee_id }})
                                        </a>
                                    @else
                                        <span class="text-muted">Not linked to employee</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Account Created</label>
                                <p class="mb-0"><i class="bi bi-calendar"></i> {{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Last Updated</label>
                                <p class="mb-0"><i class="bi bi-calendar"></i> {{ $user->updated_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions Section -->
            @if($user->role)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-check"></i> Permissions</h5>
                </div>
                <div class="card-body">
                    @php
                        $permissions = $user->role->permissions->groupBy('group');
                    @endphp

                    @if($permissions->count() > 0)
                        <div class="row">
                            @foreach($permissions as $group => $perms)
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-capitalize">{{ str_replace('_', ' ', $group) }}</h6>
                                    <ul class="list-unstyled ms-3">
                                        @foreach($perms as $permission)
                                            <li><i class="bi bi-check-circle text-success"></i> {{ $permission->display_name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No permissions assigned to this role</p>
                    @endif
                </div>
            </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
    </div>
@endsection
