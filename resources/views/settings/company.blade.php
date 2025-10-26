@extends('layouts.app')

@section('title', 'Company Settings')
@section('page-title', 'Company Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="table-card">
                <h5 class="mb-4">Company Information</h5>

                <form action="{{ route('settings.updateCompany') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name', session('company_settings.company_name', 'Your Company Name')) }}"
                                   required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="company_email"
                                   class="form-control @error('company_email') is-invalid @enderror"
                                   value="{{ old('company_email', session('company_settings.company_email', 'info@company.com')) }}"
                                   required>
                            @error('company_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="company_phone"
                                   class="form-control @error('company_phone') is-invalid @enderror"
                                   value="{{ old('company_phone', session('company_settings.company_phone', '+1234567890')) }}"
                                   required>
                            @error('company_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea name="company_address"
                                      class="form-control @error('company_address') is-invalid @enderror"
                                      rows="3"
                                      required>{{ old('company_address', session('company_settings.company_address', '123 Business Street, City, Country')) }}</textarea>
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Website</label>
                            <input type="url" name="company_website"
                                   class="form-control @error('company_website') is-invalid @enderror"
                                   value="{{ old('company_website', session('company_settings.company_website', 'https://company.com')) }}">
                            @error('company_website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Save Settings
                            </button>
                            <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif
@endsection
