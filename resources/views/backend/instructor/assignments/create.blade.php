@extends('backend.master')

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Create Assignment</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('assignments.index') }}">Assignments</a>
                <a href="#">Create</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-12">
                <div class="white-box" style="border-radius:12px;">
                    <h4 class="mb-30" style="font-weight:600;">Assignment Details</h4>

                    <form action="{{ route('assignments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Course *</label>
                            <select name="course_id" class="form-control" style="border-radius:8px;" required>
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Assignment Title *</label>
                            <input type="text" name="title" class="form-control" style="border-radius:8px;" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Instructions *</label>
                            <textarea name="description" class="form-control" rows="5" style="border-radius:8px;" required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;">Maximum Marks *</label>
                                <input type="number" name="max_marks" class="form-control" value="100" min="1" style="border-radius:8px;" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;">Due Date & Time *</label>
                                <input type="datetime-local" name="due_date" class="form-control" style="border-radius:8px;" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-weight:600;">Upload Reference File (optional)</label>
                            <input type="file" name="file" class="form-control-file">
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('assignments.index') }}" class="text-muted"><i class="ti-angle-left mr-1"></i> Cancel</a>
                            <button type="submit" class="primary-btn fix-gr-bg" style="border-radius:25px; padding:10px 32px;">Create Assignment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
