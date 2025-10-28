@extends('layouts.app')

@section('title', 'Employee Documents')
@section('page-title', 'Employee Documents')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.show', $employee) }}">{{ $employee->first_name }} {{ $employee->last_name }}</a></li>
                <li class="breadcrumb-item active">Documents</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Documents - {{ $employee->first_name }} {{ $employee->last_name }}</h4>
            <p class="text-muted mb-0">{{ $employee->employee_code }}</p>
        </div>
        <a href="{{ route('employees.documents.create', $employee) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Upload Document
        </a>
    </div>

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

    <div class="row">
        @forelse($documents as $document)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <span class="badge
                            @if($document->type == 'contract') bg-primary
                            @elseif($document->type == 'certificate') bg-success
                            @elseif($document->type == 'id_document') bg-info
                            @else bg-secondary
                            @endif">
                            {{ ucwords(str_replace('_', ' ', $document->type)) }}
                        </span>
                        @if($document->expiry_date && $document->expiry_date < now())
                            <span class="badge bg-danger">Expired</span>
                        @elseif($document->expiry_date && $document->expiry_date < now()->addDays(30))
                            <span class="badge bg-warning">Expiring Soon</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-file-earmark-text"></i> {{ $document->title }}
                        </h6>
                        @if($document->description)
                            <p class="card-text text-muted small">{{ $document->description }}</p>
                        @endif
                        <div class="small text-muted">
                            <div class="mb-1">
                                <i class="bi bi-file-binary"></i> {{ strtoupper($document->file_type) }}
                                <span class="ms-2">{{ $document->file_size_formatted }}</span>
                            </div>
                            @if($document->issue_date)
                                <div class="mb-1">
                                    <i class="bi bi-calendar-event"></i> Issued: {{ $document->issue_date->format('M d, Y') }}
                                </div>
                            @endif
                            @if($document->expiry_date)
                                <div class="mb-1">
                                    <i class="bi bi-calendar-x"></i> Expires: {{ $document->expiry_date->format('M d, Y') }}
                                </div>
                            @endif
                            <div class="mb-1">
                                <i class="bi bi-person"></i> By: {{ $document->uploadedBy->name ?? 'Unknown' }}
                            </div>
                            <div>
                                <i class="bi bi-clock"></i> {{ $document->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        <a href="{{ route('employees.documents.download', [$employee, $document]) }}"
                           class="btn btn-sm btn-primary flex-fill">
                            <i class="bi bi-download"></i> Download
                        </a>
                        <form action="{{ route('employees.documents.destroy', [$employee, $document]) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this document?')"
                              class="flex-fill">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger w-100">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No documents uploaded yet. Click "Upload Document" to add one.
                </div>
            </div>
        @endforelse
    </div>
@endsection
