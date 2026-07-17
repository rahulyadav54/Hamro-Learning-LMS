@extends('backend.master')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<style>
    #calendar {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Interactive Calendar</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="#">Calendar</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Calendar view --}}
            <div class="col-xl-9 col-12 mb-4">
                <div id="calendar"></div>
            </div>

            {{-- Quick add event form --}}
            <div class="col-xl-3 col-12">
                <div class="white-box" style="border-radius:12px;">
                    <h4 class="mb-20" style="font-weight:600;">Add Event</h4>
                    <form action="#" id="addCalEventForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Event Title *</label>
                            <input type="text" name="title" id="eventTitle" placeholder="e.g. Mid-term Submission review" class="form-control" style="border-radius:8px;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Event Type *</label>
                            <select name="type" id="eventType" class="form-control" style="border-radius:8px;" required>
                                <option value="event">General Event</option>
                                <option value="class">Live Class Session</option>
                                <option value="quiz">Quiz Deadline</option>
                                <option value="assignment">Assignment Deadline</option>
                                <option value="meeting">Team Meeting</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Start Date & Time *</label>
                            <input type="datetime-local" name="start" id="eventStart" class="form-control" style="border-radius:8px;" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" style="font-weight:600;">End Date & Time</label>
                            <input type="datetime-local" name="end" id="eventEnd" class="form-control" style="border-radius:8px;">
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg w-100" style="border-radius:25px; padding:10px;">Create Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '{{ route("calendar.feed") }}',
        eventClick: function(info) {
            if (confirm("Do you want to delete this event: " + info.event.title + "?")) {
                fetch('{{ url("instructor/calendar-events/delete") }}/' + info.event.id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(res => {
                    info.event.remove();
                });
            }
        }
    });
    calendar.render();

    // Handle AJAX form submission
    document.getElementById('addCalEventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const title = document.getElementById('eventTitle').value;
        const type = document.getElementById('eventType').value;
        const start = document.getElementById('eventStart').value;
        const end = document.getElementById('eventEnd').value;

        fetch('{{ route("calendar.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                title: title,
                type: type,
                start: start.replace('T', ' '),
                end: end ? end.replace('T', ' ') : null
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                calendar.refetchEvents();
                document.getElementById('addCalEventForm').reset();
                alert('Event added successfully!');
            } else {
                alert('Failed to add event.');
            }
        });
    });
});
</script>
@endsection
