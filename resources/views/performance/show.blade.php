@extends('layouts.app')

@section('title', 'Performance Review Details')
@section('page-title', 'Performance Review Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <!-- Employee Info Card -->
            <div class="table-card mb-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                        @if($performance->employee->photo)
                            <img src="{{ asset('storage/' . $performance->employee->photo) }}"
                                 alt="{{ $performance->employee->full_name }}"
                                 class="rounded-circle me-3"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle me-3 bg-primary text-white d-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px; font-size: 32px;">
                                {{ substr($performance->employee->first_name, 0, 1) }}{{ substr($performance->employee->last_name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="mb-1">{{ $performance->employee->full_name }}</h4>
                            <p class="mb-1 text-muted">{{ $performance->employee->employee_code }} | {{ $performance->employee->position->title ?? 'N/A' }}</p>
                            <p class="mb-0 text-muted"><i class="bi bi-diagram-3"></i> {{ $performance->employee->department->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('performance.edit', $performance) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Review Date:</strong> {{ \Carbon\Carbon::parse($performance->review_date)->format('M d, Y') }}</p>
                        <p><strong>Review Period:</strong> <span class="badge bg-info">{{ $performance->review_period }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Reviewer:</strong> {{ $performance->reviewer->full_name }}</p>
                        <p><strong>Overall Rating:</strong>
                            <span class="fs-4 fw-bold text-primary">{{ number_format($performance->overall_rating, 1) }}/5</span>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $performance->overall_rating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-muted"></i>
                                @endif
                            @endfor
                        </p>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="table-card mb-4">
                <h5 class="mb-4">Performance Metrics</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Quality of Work</strong>
                                <span class="badge bg-primary">{{ number_format($performance->quality_of_work, 1) }}/5</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ ($performance->quality_of_work / 5) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Productivity</strong>
                                <span class="badge bg-primary">{{ number_format($performance->productivity, 1) }}/5</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ ($performance->productivity / 5) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Communication</strong>
                                <span class="badge bg-primary">{{ number_format($performance->communication, 1) }}/5</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ ($performance->communication / 5) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Teamwork</strong>
                                <span class="badge bg-primary">{{ number_format($performance->teamwork, 1) }}/5</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ ($performance->teamwork / 5) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Initiative</strong>
                                <span class="badge bg-primary">{{ number_format($performance->initiative, 1) }}/5</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ ($performance->initiative / 5) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Attendance & Punctuality</strong>
                                <span class="badge bg-primary">{{ number_format($performance->attendance_punctuality, 1) }}/5</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ ($performance->attendance_punctuality / 5) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback -->
            <div class="table-card mb-4">
                <h5 class="mb-4">Detailed Feedback</h5>

                @if($performance->strengths)
                <div class="mb-4">
                    <h6 class="text-success"><i class="bi bi-check-circle"></i> Strengths</h6>
                    <p class="mb-0">{{ $performance->strengths }}</p>
                </div>
                @endif

                @if($performance->areas_for_improvement)
                <div class="mb-4">
                    <h6 class="text-warning"><i class="bi bi-exclamation-triangle"></i> Areas for Improvement</h6>
                    <p class="mb-0">{{ $performance->areas_for_improvement }}</p>
                </div>
                @endif

                @if($performance->goals)
                <div class="mb-4">
                    <h6 class="text-info"><i class="bi bi-bullseye"></i> Goals for Next Period</h6>
                    <p class="mb-0">{{ $performance->goals }}</p>
                </div>
                @endif

                @if($performance->comments)
                <div>
                    <h6 class="text-secondary"><i class="bi bi-chat-text"></i> Additional Comments</h6>
                    <p class="mb-0">{{ $performance->comments }}</p>
                </div>
                @endif
            </div>

            <!-- Previous Reviews -->
            @if($previousReviews->count() > 0)
            <div class="table-card">
                <h5 class="mb-4">Previous Reviews</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Period</th>
                                <th>Overall Rating</th>
                                <th>Reviewer</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($previousReviews as $review)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($review->review_date)->format('M d, Y') }}</td>
                                <td><span class="badge bg-info">{{ $review->review_period }}</span></td>
                                <td>
                                    <strong>{{ number_format($review->overall_rating, 1) }}/5</strong>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->overall_rating)
                                            <i class="bi bi-star-fill text-warning small"></i>
                                        @endif
                                    @endfor
                                </td>
                                <td>{{ $review->reviewer->full_name }}</td>
                                <td>
                                    <a href="{{ route('performance.show', $review) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
