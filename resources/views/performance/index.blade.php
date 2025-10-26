@extends('layouts.app')

@section('title', 'Performance Reviews')
@section('page-title', 'Performance Reviews')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Reviews</h6>
                        <h3 class="mb-0">{{ $stats['total_reviews'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Average Rating</h6>
                        <h3 class="mb-0">{{ $stats['average_rating'] }}/5</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Excellent (≥4.5)</h6>
                        <h3 class="mb-0">{{ $stats['excellent_count'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Needs Improvement (<3)</h6>
                        <h3 class="mb-0">{{ $stats['needs_improvement'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="table-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Performance Reviews</h5>
            <a href="{{ route('performance.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Review
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Employee</label>
                <select name="employee" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Period</label>
                <select name="period" class="form-select">
                    <option value="">All Periods</option>
                    <option value="Q1" {{ request('period') == 'Q1' ? 'selected' : '' }}>Q1</option>
                    <option value="Q2" {{ request('period') == 'Q2' ? 'selected' : '' }}>Q2</option>
                    <option value="Q3" {{ request('period') == 'Q3' ? 'selected' : '' }}>Q3</option>
                    <option value="Q4" {{ request('period') == 'Q4' ? 'selected' : '' }}>Q4</option>
                    <option value="Mid-Year" {{ request('period') == 'Mid-Year' ? 'selected' : '' }}>Mid-Year</option>
                    <option value="Yearly" {{ request('period') == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-select">
                    <option value="">All Ratings</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 - Excellent</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 - Very Good</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 - Good</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 - Fair</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 - Poor</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Year</label>
                <select name="year" class="form-select">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </form>

        <!-- Reviews Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Reviewer</th>
                        <th>Review Date</th>
                        <th>Period</th>
                        <th>Overall Rating</th>
                        <th>Quality</th>
                        <th>Productivity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($review->employee->photo)
                                        <img src="{{ asset('storage/' . $review->employee->photo) }}"
                                             alt="{{ $review->employee->full_name }}"
                                             class="rounded-circle me-2"
                                             style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle me-2 bg-secondary text-white d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px; font-size: 14px;">
                                            {{ substr($review->employee->first_name, 0, 1) }}{{ substr($review->employee->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $review->employee->full_name }}</div>
                                        <small class="text-muted">{{ $review->employee->employee_code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $review->reviewer->full_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($review->review_date)->format('M d, Y') }}</td>
                            <td><span class="badge bg-info">{{ $review->review_period }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">{{ number_format($review->overall_rating, 1) }}</div>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->overall_rating)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </td>
                            <td>{{ number_format($review->quality_of_work, 1) }}</td>
                            <td>{{ number_format($review->productivity, 1) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('performance.show', $review) }}"
                                       class="btn btn-outline-primary"
                                       title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('performance.edit', $review) }}"
                                       class="btn btn-outline-secondary"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('performance.destroy', $review) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-outline-danger"
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No performance reviews found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $reviews->links() }}
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
