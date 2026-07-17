@extends('backend.master')

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Schedule Live Class</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('instructor.live-classes.index') }}">Live Classes</a>
                <a href="#">Schedule</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-12">
                <div class="white-box" style="border-radius:12px;">
                    <h4 class="mb-30" style="font-weight:600;">Class Configurations</h4>

                    <form action="{{ route('instructor.live-classes.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Class Topic *</label>
                            <input type="text" name="title" placeholder="e.g. Flutter State Management Live Session" class="form-control" style="border-radius:8px;" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Course *</label>
                            <select name="course_id" class="form-control" style="border-radius:8px;" required>
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;">Meeting Provider *</label>
                                <select name="host" class="form-control" style="border-radius:8px;" required>
                                    <option value="Zoom">Zoom Meetings</option>
                                    <option value="GoogleMeet">Google Meet (Simulated Classroom)</option>
                                    <option value="BBB">BigBlueButton (BBB)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;">Meeting Password (optional)</label>
                                <input type="text" name="password" placeholder="123456" class="form-control" style="border-radius:8px;">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;">Start Date & Time *</label>
                                <input type="datetime-local" name="start_time" class="form-control" style="border-radius:8px;" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;">Duration (minutes) *</label>
                                <input type="number" name="duration" value="60" min="15" class="form-control" style="border-radius:8px;" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('instructor.live-classes.index') }}" class="text-muted"><i class="ti-angle-left mr-1"></i> Cancel</a>
                            <button type="submit" class="primary-btn fix-gr-bg" style="border-radius:25px; padding:10px 32px;">Schedule Class</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
