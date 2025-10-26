@extends('layouts.app')

@section('title', 'Create Performance Review')
@section('page-title', 'Create Performance Review')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="table-card">
                <h5 class="mb-4">New Performance Review</h5>

                <form action="{{ route('performance.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <!-- Employee & Reviewer -->
                        <div class="col-md-6">
                            <label class="form-label">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} - {{ $employee->employee_code }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Reviewer <span class="text-danger">*</span></label>
                            <select name="reviewer_id" class="form-select @error('reviewer_id') is-invalid @enderror" required>
                                <option value="">Select Reviewer</option>
                                @foreach($reviewers as $reviewer)
                                    <option value="{{ $reviewer->id }}" {{ old('reviewer_id') == $reviewer->id ? 'selected' : '' }}>
                                        {{ $reviewer->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('reviewer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Review Date & Period -->
                        <div class="col-md-6">
                            <label class="form-label">Review Date <span class="text-danger">*</span></label>
                            <input type="date" name="review_date" class="form-control @error('review_date') is-invalid @enderror"
                                   value="{{ old('review_date', date('Y-m-d')) }}" required>
                            @error('review_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Review Period <span class="text-danger">*</span></label>
                            <select name="review_period" class="form-select @error('review_period') is-invalid @enderror" required>
                                <option value="">Select Period</option>
                                <option value="Q1" {{ old('review_period') == 'Q1' ? 'selected' : '' }}>Q1 (Jan-Mar)</option>
                                <option value="Q2" {{ old('review_period') == 'Q2' ? 'selected' : '' }}>Q2 (Apr-Jun)</option>
                                <option value="Q3" {{ old('review_period') == 'Q3' ? 'selected' : '' }}>Q3 (Jul-Sep)</option>
                                <option value="Q4" {{ old('review_period') == 'Q4' ? 'selected' : '' }}>Q4 (Oct-Dec)</option>
                                <option value="Mid-Year" {{ old('review_period') == 'Mid-Year' ? 'selected' : '' }}>Mid-Year</option>
                                <option value="Yearly" {{ old('review_period') == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            @error('review_period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Performance Metrics -->
                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2 mb-3">Performance Metrics (Rate 1-5)</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Quality of Work <span class="text-danger">*</span></label>
                            <input type="number" name="quality_of_work" class="form-control @error('quality_of_work') is-invalid @enderror"
                                   min="1" max="5" step="0.1" value="{{ old('quality_of_work') }}" required>
                            @error('quality_of_work')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Productivity <span class="text-danger">*</span></label>
                            <input type="number" name="productivity" class="form-control @error('productivity') is-invalid @enderror"
                                   min="1" max="5" step="0.1" value="{{ old('productivity') }}" required>
                            @error('productivity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Communication <span class="text-danger">*</span></label>
                            <input type="number" name="communication" class="form-control @error('communication') is-invalid @enderror"
                                   min="1" max="5" step="0.1" value="{{ old('communication') }}" required>
                            @error('communication')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Teamwork <span class="text-danger">*</span></label>
                            <input type="number" name="teamwork" class="form-control @error('teamwork') is-invalid @enderror"
                                   min="1" max="5" step="0.1" value="{{ old('teamwork') }}" required>
                            @error('teamwork')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Initiative <span class="text-danger">*</span></label>
                            <input type="number" name="initiative" class="form-control @error('initiative') is-invalid @enderror"
                                   min="1" max="5" step="0.1" value="{{ old('initiative') }}" required>
                            @error('initiative')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Attendance & Punctuality <span class="text-danger">*</span></label>
                            <input type="number" name="attendance_punctuality" class="form-control @error('attendance_punctuality') is-invalid @enderror"
                                   min="1" max="5" step="0.1" value="{{ old('attendance_punctuality') }}" required>
                            @error('attendance_punctuality')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Overall Rating <span class="text-danger">*</span></label>
                            <input type="number" name="overall_rating" class="form-control @error('overall_rating') is-invalid @enderror"
                                   min="1" max="5" step="0.1" value="{{ old('overall_rating') }}" required>
                            @error('overall_rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Comments Section -->
                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2 mb-3">Detailed Feedback</h6>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Strengths</label>
                            <textarea name="strengths" class="form-control @error('strengths') is-invalid @enderror"
                                      rows="3">{{ old('strengths') }}</textarea>
                            @error('strengths')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Areas for Improvement</label>
                            <textarea name="areas_for_improvement" class="form-control @error('areas_for_improvement') is-invalid @enderror"
                                      rows="3">{{ old('areas_for_improvement') }}</textarea>
                            @error('areas_for_improvement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Goals for Next Period</label>
                            <textarea name="goals" class="form-control @error('goals') is-invalid @enderror"
                                      rows="3">{{ old('goals') }}</textarea>
                            @error('goals')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Additional Comments</label>
                            <textarea name="comments" class="form-control @error('comments') is-invalid @enderror"
                                      rows="3">{{ old('comments') }}</textarea>
                            @error('comments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Submit Review
                            </button>
                            <a href="{{ route('performance.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
