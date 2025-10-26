@extends('layouts.app')

@section('title', 'Generate Payroll')
@section('page-title', 'Generate Payroll')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li>
                <li class="breadcrumb-item active">Generate</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="table-card">
                <h5 class="mb-4">Generate New Payroll</h5>

                <form action="{{ route('payroll.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select class="form-select @error('employee_id') is-invalid @enderror"
                                    id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                            data-salary="{{ $employee->basic_salary ?? 0 }}"
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} ({{ $employee->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
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

                        <div class="col-md-3 mb-3">
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
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">Salary Components</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="basic_salary" class="form-label">Basic Salary <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('basic_salary') is-invalid @enderror"
                                   id="basic_salary" name="basic_salary" value="{{ old('basic_salary', 0) }}" required>
                            @error('basic_salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="working_days" class="form-label">Working Days</label>
                            <input type="number" class="form-control @error('working_days') is-invalid @enderror"
                                   id="working_days" name="working_days" value="{{ old('working_days', 26) }}">
                            @error('working_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h6 class="mb-3 text-success">Allowances</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hra" class="form-label">HRA (House Rent Allowance)</label>
                            <input type="number" step="0.01" class="form-control @error('hra') is-invalid @enderror"
                                   id="hra" name="hra" value="{{ old('hra', 0) }}">
                            @error('hra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="da" class="form-label">DA (Dearness Allowance)</label>
                            <input type="number" step="0.01" class="form-control @error('da') is-invalid @enderror"
                                   id="da" name="da" value="{{ old('da', 0) }}">
                            @error('da')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="medical_allowance" class="form-label">Medical Allowance</label>
                            <input type="number" step="0.01" class="form-control @error('medical_allowance') is-invalid @enderror"
                                   id="medical_allowance" name="medical_allowance" value="{{ old('medical_allowance', 0) }}">
                            @error('medical_allowance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="transport_allowance" class="form-label">Transport Allowance</label>
                            <input type="number" step="0.01" class="form-control @error('transport_allowance') is-invalid @enderror"
                                   id="transport_allowance" name="transport_allowance" value="{{ old('transport_allowance', 0) }}">
                            @error('transport_allowance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="other_allowance" class="form-label">Other Allowances</label>
                            <input type="number" step="0.01" class="form-control @error('other_allowance') is-invalid @enderror"
                                   id="other_allowance" name="other_allowance" value="{{ old('other_allowance', 0) }}">
                            @error('other_allowance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h6 class="mb-3 text-danger">Deductions</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pf" class="form-label">PF (Provident Fund)</label>
                            <input type="number" step="0.01" class="form-control @error('pf') is-invalid @enderror"
                                   id="pf" name="pf" value="{{ old('pf', 0) }}">
                            @error('pf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="esi" class="form-label">ESI (Employee State Insurance)</label>
                            <input type="number" step="0.01" class="form-control @error('esi') is-invalid @enderror"
                                   id="esi" name="esi" value="{{ old('esi', 0) }}">
                            @error('esi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tds" class="form-label">TDS (Tax Deducted at Source)</label>
                            <input type="number" step="0.01" class="form-control @error('tds') is-invalid @enderror"
                                   id="tds" name="tds" value="{{ old('tds', 0) }}">
                            @error('tds')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="loan" class="form-label">Loan Deduction</label>
                            <input type="number" step="0.01" class="form-control @error('loan') is-invalid @enderror"
                                   id="loan" name="loan" value="{{ old('loan', 0) }}">
                            @error('loan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="other_deduction" class="form-label">Other Deductions</label>
                            <input type="number" step="0.01" class="form-control @error('other_deduction') is-invalid @enderror"
                                   id="other_deduction" name="other_deduction" value="{{ old('other_deduction', 0) }}">
                            @error('other_deduction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Total Allowances:</strong> ₹<span id="total_allowances">0.00</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Deductions:</strong> ₹<span id="total_deductions">0.00</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Net Salary:</strong> <span class="text-success fs-5">₹<span id="net_salary">0.00</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Generate Payroll
                        </button>
                        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
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
    // Auto-calculate totals
    function calculateTotals() {
        const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;

        // Calculate allowances
        const hra = parseFloat(document.getElementById('hra').value) || 0;
        const da = parseFloat(document.getElementById('da').value) || 0;
        const medical = parseFloat(document.getElementById('medical_allowance').value) || 0;
        const transport = parseFloat(document.getElementById('transport_allowance').value) || 0;
        const otherAllow = parseFloat(document.getElementById('other_allowance').value) || 0;
        const totalAllowances = hra + da + medical + transport + otherAllow;

        // Calculate deductions
        const pf = parseFloat(document.getElementById('pf').value) || 0;
        const esi = parseFloat(document.getElementById('esi').value) || 0;
        const tds = parseFloat(document.getElementById('tds').value) || 0;
        const loan = parseFloat(document.getElementById('loan').value) || 0;
        const otherDed = parseFloat(document.getElementById('other_deduction').value) || 0;
        const totalDeductions = pf + esi + tds + loan + otherDed;

        // Calculate net salary
        const grossSalary = basicSalary + totalAllowances;
        const netSalary = grossSalary - totalDeductions;

        // Update display
        document.getElementById('total_allowances').textContent = totalAllowances.toFixed(2);
        document.getElementById('total_deductions').textContent = totalDeductions.toFixed(2);
        document.getElementById('net_salary').textContent = netSalary.toFixed(2);
    }

    // Add event listeners to all input fields
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });

    // Load employee salary when selected
    document.getElementById('employee_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const salary = selectedOption.getAttribute('data-salary');
        if (salary) {
            document.getElementById('basic_salary').value = salary;
            calculateTotals();
        }
    });

    // Initial calculation
    calculateTotals();
</script>
@endpush
