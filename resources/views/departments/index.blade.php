@extends('layouts.app')

@section('title', 'Departments')
@section('page-title', 'Departments')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Manage Departments</h5>
            <p class="text-muted mb-0">View and manage organization departments</p>
        </div>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Department
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="table-card">
                <form method="GET" action="{{ route('departments.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Search departments..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="active">
                            <option value="">All Status</option>
                            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('departments.index') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($departments as $department)
            <div class="col-md-4 mb-4">
                <div class="table-card h-100">
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div class="icon-box" style="background-color: {{ $department->color ?? '#714b67' }}20; color: {{ $department->color ?? '#714b67' }}">
                                <i class="bi bi-building"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">
                                <a href="{{ route('departments.show', $department) }}" class="text-decoration-none text-dark">
                                    {{ $department->name }}
                                </a>
                            </h5>
                            <span class="badge badge-status-{{ $department->active ? 'active' : 'inactive' }}">
                                {{ $department->active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    @if($department->description)
                        <p class="text-muted small mb-3">{{ Str::limit($department->description, 100) }}</p>
                    @endif

                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-sm mb-2">
                            <span class="text-muted">
                                <i class="bi bi-person me-1"></i> Manager:
                            </span>
                            <span class="fw-medium">
                                {{ $department->manager->full_name ?? 'Not assigned' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between text-sm mb-2">
                            <span class="text-muted">
                                <i class="bi bi-people me-1"></i> Employees:
                            </span>
                            <span class="fw-medium">{{ $department->employees_count }}</span>
                        </div>
                        @if($department->parent)
                            <div class="d-flex justify-content-between text-sm">
                                <span class="text-muted">
                                    <i class="bi bi-diagram-3 me-1"></i> Parent:
                                </span>
                                <span class="fw-medium">{{ $department->parent->name }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('departments.show', $department) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure? This will affect {{ $department->employees_count }} employees.')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="table-card text-center py-5">
                    <i class="bi bi-building" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted mt-3 mb-0">No departments found</p>
                    <a href="{{ route('departments.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Create First Department
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($departments->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $departments->links() }}
        </div>
    @endif
@endsection
