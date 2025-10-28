@extends('layouts.app')

@section('title', 'Upload Document')
@section('page-title', 'Upload Document')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.show', $employee) }}">{{ $employee->first_name }} {{ $employee->last_name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.documents.index', $employee) }}">Documents</a></li>
                <li class="breadcrumb-item active">Upload</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-upload"></i> Upload Document for {{ $employee->first_name }} {{ $employee->last_name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.documents.store', $employee) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Document Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Document Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="contract" {{ old('type') == 'contract' ? 'selected' : '' }}>Employment Contract</option>
                                <option value="certificate" {{ old('type') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                                <option value="id_document" {{ old('type') == 'id_document' ? 'selected' : '' }}>ID Document</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">File <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Max file size: 10MB. Supported formats: PDF, DOC, DOCX, JPG, PNG</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="issue_date" class="form-label">Issue Date</label>
                                <input type="date" name="issue_date" id="issue_date"
                                       class="form-control @error('issue_date') is-invalid @enderror"
                                       value="{{ old('issue_date') }}">
                                @error('issue_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" id="expiry_date"
                                       class="form-control @error('expiry_date') is-invalid @enderror"
                                       value="{{ old('expiry_date') }}">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-upload"></i> Upload Document
                            </button>
                            <a href="{{ route('employees.documents.index', $employee) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
