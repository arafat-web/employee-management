@extends('layouts.app')

@section('title', 'Leave Types')
@section('page-title', 'Leave Types Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="table-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Leave Types</h5>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeaveTypeModal">
                            <i class="bi bi-plus-circle"></i> Add Leave Type
                        </button>
                        <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Days/Year</th>
                                <th>Max Consecutive</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveTypes as $type)
                                <tr>
                                    <td><strong>{{ $type->name }}</strong></td>
                                    <td><span class="badge bg-info">{{ $type->code }}</span></td>
                                    <td>{{ $type->days_per_year }} days</td>
                                    <td>{{ $type->max_consecutive_days ?? 'No limit' }}</td>
                                    <td>
                                        @if($type->is_paid)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-secondary">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editLeaveType({{ $type }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('settings.deleteLeaveType', $type) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this leave type?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No leave types found. Add one to get started.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addLeaveTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('settings.storeLeaveType') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Code *</label>
                        <input type="text" name="code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Days per Year *</label>
                        <input type="number" name="days_per_year" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Consecutive Days</label>
                        <input type="number" name="max_consecutive_days" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <select name="is_paid" class="form-select" required>
                            <option value="1">Paid</option>
                            <option value="0">Unpaid</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session('success') }}');
    });
</script>
@endif
@endsection
