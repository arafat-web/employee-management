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
                                <th>Days Allowed</th>
                                <th>Requires Approval</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveTypes as $type)
                                <tr>
                                    <td>
                                        <strong>{{ $type->name }}</strong>
                                        @if($type->color)
                                            <span class="badge" style="background-color: {{ $type->color }};">&nbsp;&nbsp;&nbsp;</span>
                                        @endif
                                    </td>
                                    <td>{{ $type->days_allowed }} days</td>
                                    <td>
                                        @if($type->requires_approval)
                                            <span class="badge bg-warning">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($type->is_paid)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-secondary">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($type->active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editLeaveType({{ json_encode($type) }})">
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
                        <label class="form-label">Days Allowed *</label>
                        <input type="number" name="days_allowed" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" class="form-control" value="#3498db">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="requires_approval" class="form-check-input" id="requiresApprovalCheck" value="1" checked>
                            <label class="form-check-label" for="requiresApprovalCheck">Requires Approval</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <select name="is_paid" class="form-select" required>
                            <option value="1">Paid</option>
                            <option value="0">Unpaid</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="active" class="form-check-input" id="activeCheck" value="1" checked>
                            <label class="form-check-label" for="activeCheck">Active</label>
                        </div>
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

<!-- Edit Modal -->
<div class="modal fade" id="editLeaveTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editLeaveTypeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Days Allowed *</label>
                        <input type="number" name="days_allowed" id="edit_days_allowed" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" id="edit_color" class="form-control">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="requires_approval" id="edit_requires_approval" class="form-check-input" value="1">
                            <label class="form-check-label" for="edit_requires_approval">Requires Approval</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <select name="is_paid" id="edit_is_paid" class="form-select" required>
                            <option value="1">Paid</option>
                            <option value="0">Unpaid</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="active" id="edit_active" class="form-check-input" value="1">
                            <label class="form-check-label" for="edit_active">Active</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
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

<script>
function editLeaveType(leaveType) {
    // Set form action URL
    document.getElementById('editLeaveTypeForm').action = '/settings/leave-types/' + leaveType.id;

    // Populate form fields
    document.getElementById('edit_name').value = leaveType.name;
    document.getElementById('edit_days_allowed').value = leaveType.days_allowed;
    document.getElementById('edit_color').value = leaveType.color || '#3498db';
    document.getElementById('edit_requires_approval').checked = leaveType.requires_approval ? true : false;
    document.getElementById('edit_is_paid').value = leaveType.is_paid ? '1' : '0';
    document.getElementById('edit_active').checked = leaveType.active ? true : false;
    document.getElementById('edit_description').value = leaveType.description || '';

    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('editLeaveTypeModal'));
    modal.show();
}
</script>
@endsection
