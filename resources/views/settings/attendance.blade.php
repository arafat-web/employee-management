@extends('layouts.app')

@section('title', 'Attendance Settings')
@section('page-title', 'Attendance Settings')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
                <li class="breadcrumb-item active">Attendance</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Attendance Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.updateAttendance') }}" method="POST">
                        @csrf

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> <strong>Note:</strong> These settings will apply to all employees' check-in and check-out functionality.
                        </div>

                        <h6 class="border-bottom pb-2 mb-3">Check-in Time Limits</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="check_in_start" class="form-label">Earliest Check-in Time</label>
                                <input type="time" name="check_in_start" id="check_in_start"
                                       class="form-control @error('check_in_start') is-invalid @enderror"
                                       value="{{ old('check_in_start', $settings->check_in_start) }}" required>
                                @error('check_in_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Employees cannot check-in before this time</small>
                            </div>

                            <div class="col-md-6">
                                <label for="check_in_end" class="form-label">Latest Check-in Time</label>
                                <input type="time" name="check_in_end" id="check_in_end"
                                       class="form-control @error('check_in_end') is-invalid @enderror"
                                       value="{{ old('check_in_end', $settings->check_in_end) }}" required>
                                @error('check_in_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Employees cannot check-in after this time</small>
                            </div>
                        </div>

                        <h6 class="border-bottom pb-2 mb-3">Check-out Time Limits</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="check_out_start" class="form-label">Earliest Check-out Time</label>
                                <input type="time" name="check_out_start" id="check_out_start"
                                       class="form-control @error('check_out_start') is-invalid @enderror"
                                       value="{{ old('check_out_start', $settings->check_out_start) }}" required>
                                @error('check_out_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Employees cannot check-out before this time</small>
                            </div>

                            <div class="col-md-6">
                                <label for="check_out_end" class="form-label">Latest Check-out Time</label>
                                <input type="time" name="check_out_end" id="check_out_end"
                                       class="form-control @error('check_out_end') is-invalid @enderror"
                                       value="{{ old('check_out_end', $settings->check_out_end) }}" required>
                                @error('check_out_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Employees cannot check-out after this time</small>
                            </div>
                        </div>

                        <h6 class="border-bottom pb-2 mb-3">Official Work Hours</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="work_start_time" class="form-label">Work Start Time</label>
                                <input type="time" name="work_start_time" id="work_start_time"
                                       class="form-control @error('work_start_time') is-invalid @enderror"
                                       value="{{ old('work_start_time', $settings->work_start_time) }}" required>
                                @error('work_start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Official work day starts at this time</small>
                            </div>

                            <div class="col-md-6">
                                <label for="work_end_time" class="form-label">Work End Time</label>
                                <input type="time" name="work_end_time" id="work_end_time"
                                       class="form-control @error('work_end_time') is-invalid @enderror"
                                       value="{{ old('work_end_time', $settings->work_end_time) }}" required>
                                @error('work_end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Official work day ends at this time</small>
                            </div>
                        </div>

                        <h6 class="border-bottom pb-2 mb-3">Attendance Rules</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="late_threshold_minutes" class="form-label">Late Threshold (Minutes)</label>
                                <input type="number" name="late_threshold_minutes" id="late_threshold_minutes"
                                       class="form-control @error('late_threshold_minutes') is-invalid @enderror"
                                       value="{{ old('late_threshold_minutes', $settings->late_threshold_minutes) }}"
                                       min="0" max="120" required>
                                @error('late_threshold_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minutes after work start time to mark as late</small>
                            </div>

                            <div class="col-md-6">
                                <label for="early_leave_threshold_minutes" class="form-label">Early Leave Threshold (Minutes)</label>
                                <input type="number" name="early_leave_threshold_minutes" id="early_leave_threshold_minutes"
                                       class="form-control @error('early_leave_threshold_minutes') is-invalid @enderror"
                                       value="{{ old('early_leave_threshold_minutes', $settings->early_leave_threshold_minutes) }}"
                                       min="0" max="120" required>
                                @error('early_leave_threshold_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minutes before work end time to mark as early leave</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="half_day_hours" class="form-label">Half Day Hours</label>
                                <input type="number" name="half_day_hours" id="half_day_hours"
                                       class="form-control @error('half_day_hours') is-invalid @enderror"
                                       value="{{ old('half_day_hours', $settings->half_day_hours) }}"
                                       min="1" max="12" required>
                                @error('half_day_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum hours worked to consider as half day</small>
                            </div>

                            <div class="col-md-6">
                                <label for="full_day_hours" class="form-label">Full Day Hours</label>
                                <input type="number" name="full_day_hours" id="full_day_hours"
                                       class="form-control @error('full_day_hours') is-invalid @enderror"
                                       value="{{ old('full_day_hours', $settings->full_day_hours) }}"
                                       min="1" max="24" required>
                                @error('full_day_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum hours worked to consider as full day</small>
                            </div>
                        </div>

                        <h6 class="border-bottom pb-2 mb-3">Additional Options</h6>
                        <div class="mb-4">
                            <div class="form-check form-switch mb-3">
                                <input type="hidden" name="allow_weekend_checkin" value="0">
                                <input type="checkbox" class="form-check-input" id="allow_weekend_checkin"
                                       name="allow_weekend_checkin" value="1"
                                       {{ old('allow_weekend_checkin', $settings->allow_weekend_checkin) ? 'checked' : '' }}>
                                <label class="form-check-label" for="allow_weekend_checkin">
                                    Allow Weekend Check-in
                                </label>
                                <div class="form-text">Enable employees to check-in on Saturdays and Sundays</div>
                            </div>

                            <div class="form-check form-switch">
                                <input type="hidden" name="require_checkout" value="0">
                                <input type="checkbox" class="form-check-input" id="require_checkout"
                                       name="require_checkout" value="1"
                                       {{ old('require_checkout', $settings->require_checkout) ? 'checked' : '' }}>
                                <label class="form-check-label" for="require_checkout">
                                    Require Check-out
                                </label>
                                <div class="form-text">Employees must check-out before leaving</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Save Attendance Settings
                            </button>
                            <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Settings
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-eye"></i> Current Settings Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Check-in Window:</span>
                                    <strong>{{ $settings->check_in_start }} - {{ $settings->check_in_end }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Check-out Window:</span>
                                    <strong>{{ $settings->check_out_start }} - {{ $settings->check_out_end }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Work Hours:</span>
                                    <strong>{{ $settings->work_start_time }} - {{ $settings->work_end_time }}</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Late After:</span>
                                    <strong>{{ $settings->late_threshold_minutes }} minutes</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Early Leave Before:</span>
                                    <strong>{{ $settings->early_leave_threshold_minutes }} minutes</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Full Day:</span>
                                    <strong>{{ $settings->full_day_hours }} hours</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
