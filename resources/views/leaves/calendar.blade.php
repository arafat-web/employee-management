@extends('layouts.app')

@section('title', 'Leave Calendar')
@section('page-title', 'Leave Calendar')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leaves</a></li>
                <li class="breadcrumb-item active">Calendar</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar3"></i> Leave Calendar</h5>
                    <div>
                        <a href="{{ route('leaves.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-list"></i> List View
                        </a>
                        <a href="{{ route('leaves.create') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle"></i> Request Leave
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Legend -->
                    <div class="mb-3 d-flex gap-3 flex-wrap">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning me-2">Pending</span>
                            <small>Pending Approval</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2">Approved</span>
                            <small>Approved Leave</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-danger me-2">Rejected</span>
                            <small>Rejected Leave</small>
                        </div>
                    </div>

                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Detail Modal -->
    <div class="modal fade" id="leaveDetailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Leave Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="leaveDetailContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: function(info, successCallback, failureCallback) {
            fetch('/leaves/calendar/data')
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => failureCallback(error));
        },
        eventClick: function(info) {
            info.jsEvent.preventDefault();

            const event = info.event;
            const leaveId = event.id;

            fetch(`/leaves/${leaveId}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const content = doc.querySelector('.card-body');

                    if (content) {
                        document.getElementById('leaveDetailContent').innerHTML = content.innerHTML;
                    } else {
                        document.getElementById('leaveDetailContent').innerHTML = `
                            <div class="p-3">
                                <h6>${event.title}</h6>
                                <p><strong>Start:</strong> ${event.start.toLocaleDateString()}</p>
                                <p><strong>End:</strong> ${event.end ? event.end.toLocaleDateString() : 'N/A'}</p>
                                <p><strong>Status:</strong> ${event.extendedProps.status}</p>
                            </div>
                        `;
                    }

                    const modal = new bootstrap.Modal(document.getElementById('leaveDetailModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching leave details:', error);
                    document.getElementById('leaveDetailContent').innerHTML = `
                        <div class="alert alert-danger">Error loading leave details</div>
                    `;
                    const modal = new bootstrap.Modal(document.getElementById('leaveDetailModal'));
                    modal.show();
                });
        },
        eventColor: '#714b67',
        displayEventTime: false,
        height: 'auto',
        aspectRatio: 1.8
    });

    calendar.render();
});
</script>
@endsection
