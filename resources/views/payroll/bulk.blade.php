@extends('layouts.app')

@section('title', 'Bulk Payroll Generation')
@section('page-title', 'Bulk Payroll Generation')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li>
                <li class="breadcrumb-item active">Bulk Generate</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="table-card">
                <h5 class="mb-4">Generate Payroll for Multiple Employees</h5>

                <form action="{{ route('payroll.bulkStore') }}" method="POST">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="month" class="form-label">Month <span class="text-danger">*</span></label>
                            <select class="form-select @error('month') is-invalid @enderror"
                                    id="month" name="month" required>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('month', now()->month) == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                            <select class="form-select @error('year') is-invalid @enderror"
                                    id="year" name="year" required>
                                @for($y = now()->year; $y >= now()->year - 2; $y--)
                                    <option value="{{ $y }}" {{ old('year', now()->year) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="department_id" class="form-label">Filter by Department</label>
                            <select class="form-select" id="department_id" name="department_id">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> Select employees below to generate payroll. Default salary components will be calculated automatically based on employee records.
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="select_all">
                            <label class="form-check-label fw-bold" for="select_all">
                                Select All Employees
                            </label>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="header_check" class="form-check-input">
                                    </th>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Basic Salary</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                                   class="form-check-input employee-checkbox"
                                                   data-salary="{{ $employee->basic_salary ?? 0 }}">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($employee->photo)
                                                    <img src="{{ asset('storage/' . $employee->photo) }}"
                                                         alt="{{ $employee->full_name }}"
                                                         class="rounded-circle me-2"
                                                         style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-2"
                                                         style="width: 32px; height: 32px; font-size: 12px;">
                                                        {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    {{ $employee->full_name }}
                                                    <br>
                                                    <small class="text-muted">{{ $employee->employee_code }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                        <td>{{ $employee->position->name ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($employee->basic_salary ?? 0, 2) }}</td>
                                        <td>
                                            @if($employee->hasPayrollFor(request('month', now()->month), request('year', now()->year)))
                                                <span class="badge bg-success">Already Generated</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-primary mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Selected Employees:</strong> <span id="selected_count">0</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Basic Salary:</strong> ₹<span id="total_salary">0.00</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Estimated Net:</strong> ₹<span id="estimated_net">0.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-files"></i> Generate Payroll for Selected Employees
                        </button>
                        <a href="{{ route('payroll.index') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Select all functionality
    document.getElementById('select_all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.employee-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateSummary();
    });

    // Update summary when checkboxes change
    document.querySelectorAll('.employee-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSummary);
    });

    function updateSummary() {
        const checkboxes = document.querySelectorAll('.employee-checkbox:checked');
        let count = 0;
        let totalSalary = 0;

        checkboxes.forEach(cb => {
            count++;
            totalSalary += parseFloat(cb.getAttribute('data-salary')) || 0;
        });

        // Rough estimation (basic + 20% allowances - 12% deductions)
        const estimatedNet = totalSalary * 1.08;

        document.getElementById('selected_count').textContent = count;
        document.getElementById('total_salary').textContent = totalSalary.toFixed(2);
        document.getElementById('estimated_net').textContent = estimatedNet.toFixed(2);
    }

    // Filter by department
    document.getElementById('department_id').addEventListener('change', function() {
        const departmentId = this.value;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            if (!departmentId) {
                row.style.display = '';
            } else {
                // This is a simple client-side filter
                // In production, you'd want to reload with filters
                row.style.display = '';
            }
        });
    });

    // Initial summary
    updateSummary();
</script>
@endpush
