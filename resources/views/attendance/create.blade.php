@extends('layouts.app')

@section('title', 'Mark Attendance')
@section('page-title', 'Mark Attendance')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li>
                <li class="breadcrumb-item active">Mark Attendance</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="table-card">
                <div class="text-center mb-4">
                    <h4>Quick Check-In</h4>
                    <p class="text-muted">Mark your attendance for today</p>
                    <div class="display-6 text-primary">{{ now()->format('l, F d, Y') }}</div>
                    <div class="h2 text-muted mt-2" id="current-time">{{ now()->format('h:i:s A') }}</div>
                </div>

                @if($todayAttendance)
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        You have already checked in today at {{ $todayAttendance->check_in }}
                        @if($todayAttendance->check_out)
                            and checked out at {{ $todayAttendance->check_out }}
                        @else
                            <form action="{{ route('attendance.employee.checkOut') }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="bi bi-box-arrow-right"></i> Check Out Now
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <form action="{{ route('attendance.employee.checkIn') }}" method="POST">
                        @csrf
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-box-arrow-in-right"></i> Check In Now
                            </button>
                        </div>
                    </form>
                @endif

                <hr class="my-4">

                <h5 class="mb-3">Manual Attendance Entry</h5>
                <form action="{{ route('attendance.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select class="form-select @error('employee_id') is-invalid @enderror"
                                    id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} ({{ $employee->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                   id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="check_in" class="form-label">Check In <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('check_in') is-invalid @enderror"
                                   id="check_in" name="check_in" value="{{ old('check_in') }}" required>
                            @error('check_in')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="check_out" class="form-label">Check Out</label>
                            <input type="time" class="form-control @error('check_out') is-invalid @enderror"
                                   id="check_out" name="check_out" value="{{ old('check_out') }}">
                            @error('check_out')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Late</option>
                                <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Mark Attendance
                        </button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
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
    // Update current time every second
    setInterval(function() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour12: true });
        document.getElementById('current-time').textContent = timeString;
    }, 1000);
</script>
@endpush
