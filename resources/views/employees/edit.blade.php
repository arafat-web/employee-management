@extends('layouts.app')

@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.show', $employee) }}">{{ $employee->full_name }}</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-card">
                <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="accordion accordion-flush" id="employeeAccordion">
                        <!-- Personal Information -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPersonal">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#personalInfo" aria-expanded="true" aria-controls="personalInfo">
                                    <i class="bi bi-person me-2"></i> Personal Information
                                </button>
                            </h2>
                            <div id="personalInfo" class="accordion-collapse collapse show" aria-labelledby="headingPersonal" data-bs-parent="#employeeAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                                   id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                                   id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="employee_code" class="form-label">Employee Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('employee_code') is-invalid @enderror"
                                                   id="employee_code" name="employee_code" value="{{ old('employee_code', $employee->employee_code) }}" required>
                                            @error('employee_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="mobile" class="form-label">Mobile</label>
                                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror"
                                                   id="mobile" name="mobile" value="{{ old('mobile', $employee->mobile) }}">
                                            @error('mobile')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}">
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender', $employee->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="marital_status" class="form-label">Marital Status</label>
                                            <select class="form-select @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status">
                                                <option value="">Select Status</option>
                                                <option value="single" {{ old('marital_status', $employee->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                                <option value="married" {{ old('marital_status', $employee->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                                <option value="divorced" {{ old('marital_status', $employee->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                            </select>
                                            @error('marital_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="photo" class="form-label">Photo</label>
                                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                                   id="photo" name="photo" accept="image/*">
                                            @error('photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if($employee->photo)
                                                <small class="text-muted">Current photo exists</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Work Information -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingWork">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#workInfo" aria-expanded="false" aria-controls="workInfo">
                                    <i class="bi bi-briefcase me-2"></i> Work Information
                                </button>
                            </h2>
                            <div id="workInfo" class="accordion-collapse collapse" aria-labelledby="headingWork" data-bs-parent="#employeeAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                                            <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                                                <option value="">Select Department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="position_id" class="form-label">Position <span class="text-danger">*</span></label>
                                            <select class="form-select @error('position_id') is-invalid @enderror" id="position_id" name="position_id" required>
                                                <option value="">Select Position</option>
                                                @foreach($positions as $position)
                                                    <option value="{{ $position->id }}" {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                                        {{ $position->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('position_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="manager_id" class="form-label">Manager</label>
                                            <select class="form-select @error('manager_id') is-invalid @enderror" id="manager_id" name="manager_id">
                                                <option value="">Select Manager</option>
                                                @foreach($managers as $manager)
                                                    @if($manager->id != $employee->id)
                                                        <option value="{{ $manager->id }}" {{ old('manager_id', $employee->manager_id) == $manager->id ? 'selected' : '' }}>
                                                            {{ $manager->full_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('manager_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="joining_date" class="form-label">Joining Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('joining_date') is-invalid @enderror"
                                                   id="joining_date" name="joining_date" value="{{ old('joining_date', $employee->joining_date?->format('Y-m-d')) }}" required>
                                            @error('joining_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="work_email" class="form-label">Work Email</label>
                                            <input type="email" class="form-control @error('work_email') is-invalid @enderror"
                                                   id="work_email" name="work_email" value="{{ old('work_email', $employee->work_email) }}">
                                            @error('work_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="work_location" class="form-label">Work Location</label>
                                            <input type="text" class="form-control @error('work_location') is-invalid @enderror"
                                                   id="work_location" name="work_location" value="{{ old('work_location', $employee->work_location) }}">
                                            @error('work_location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                                <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="on_leave" {{ old('status', $employee->status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                                <option value="resigned" {{ old('status', $employee->status) == 'resigned' ? 'selected' : '' }}>Resigned</option>
                                                <option value="terminated" {{ old('status', $employee->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAddress">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#addressInfo" aria-expanded="false" aria-controls="addressInfo">
                                    <i class="bi bi-geo-alt me-2"></i> Address Information
                                </button>
                            </h2>
                            <div id="addressInfo" class="accordion-collapse collapse" aria-labelledby="headingAddress" data-bs-parent="#employeeAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                      id="address" name="address" rows="2">{{ old('address', $employee->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                                   id="city" name="city" value="{{ old('city', $employee->city) }}">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" class="form-control @error('state') is-invalid @enderror"
                                                   id="state" name="state" value="{{ old('state', $employee->state) }}">
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="zip_code" class="form-label">ZIP Code</label>
                                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                                   id="zip_code" name="zip_code" value="{{ old('zip_code', $employee->zip_code) }}">
                                            @error('zip_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="country" class="form-label">Country</label>
                                            <input type="text" class="form-control @error('country') is-invalid @enderror"
                                                   id="country" name="country" value="{{ old('country', $employee->country) }}">
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Employee
                        </button>
                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
