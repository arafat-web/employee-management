@extends('layouts.app')

@section('title', 'Import Employees')
@section('page-title', 'Import Employees')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                <li class="breadcrumb-item active">Import</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-upload"></i> Import Employees from Excel/CSV</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Instructions</h6>
                        <ol class="mb-0">
                            <li>Download the import template using the button below</li>
                            <li>Fill in the employee data in the template</li>
                            <li>Save the file and upload it using the form below</li>
                            <li>Make sure the email addresses are unique</li>
                            <li>Required fields: first_name, last_name, email, department, position</li>
                        </ol>
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('employees.template') }}" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-download"></i> Download Import Template
                        </a>
                    </div>

                    <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Choose Excel/CSV File</label>
                            <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Accepted formats: XLSX, XLS, CSV (Max: 2MB)</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-upload"></i> Import Employees
                            </button>
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-table"></i> Template Fields Reference</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Required</th>
                                    <th>Example</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>employee_code</td>
                                    <td><span class="badge bg-secondary">Optional</span></td>
                                    <td>EMP0001</td>
                                    <td>Auto-generated if empty</td>
                                </tr>
                                <tr>
                                    <td>first_name</td>
                                    <td><span class="badge bg-danger">Required</span></td>
                                    <td>John</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>last_name</td>
                                    <td><span class="badge bg-danger">Required</span></td>
                                    <td>Doe</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>email</td>
                                    <td><span class="badge bg-danger">Required</span></td>
                                    <td>john.doe@example.com</td>
                                    <td>Must be unique</td>
                                </tr>
                                <tr>
                                    <td>phone</td>
                                    <td><span class="badge bg-secondary">Optional</span></td>
                                    <td>+1234567890</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>department</td>
                                    <td><span class="badge bg-danger">Required</span></td>
                                    <td>IT Department</td>
                                    <td>Will be created if doesn't exist</td>
                                </tr>
                                <tr>
                                    <td>position</td>
                                    <td><span class="badge bg-danger">Required</span></td>
                                    <td>Software Developer</td>
                                    <td>Will be created if doesn't exist</td>
                                </tr>
                                <tr>
                                    <td>join_date</td>
                                    <td><span class="badge bg-secondary">Optional</span></td>
                                    <td>2025-01-01</td>
                                    <td>Format: YYYY-MM-DD</td>
                                </tr>
                                <tr>
                                    <td>salary</td>
                                    <td><span class="badge bg-secondary">Optional</span></td>
                                    <td>50000</td>
                                    <td>Numeric value</td>
                                </tr>
                                <tr>
                                    <td>status</td>
                                    <td><span class="badge bg-secondary">Optional</span></td>
                                    <td>active</td>
                                    <td>active, inactive, on_leave, terminated</td>
                                </tr>
                                <tr>
                                    <td>gender</td>
                                    <td><span class="badge bg-secondary">Optional</span></td>
                                    <td>male</td>
                                    <td>male, female, other</td>
                                </tr>
                                <tr>
                                    <td>password</td>
                                    <td><span class="badge bg-secondary">Optional</span></td>
                                    <td>password123</td>
                                    <td>Default: password123</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
