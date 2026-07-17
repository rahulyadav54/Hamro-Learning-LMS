@extends('backend.master')

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Edit Assignment</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('assignments.index') }}">Assignments</a>
                <a href="#">Edit</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-12">
                <div class="white-box" style="border-radius:12px;">
                    <h4 class="mb-30" style="font-weight:600;">Edit Details</h4>

                    <form action="{{ route('assignments.update', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Course *</label>
                            <select name="course_id" class="form-control" style="border-radius:8px;" required>
                                @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ $assignment->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Assignment Title *</label>
                            <input type="text" name="title" value="{{ $assignment->title }}" class="form-control" style="border-radius:8px;" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Instructions *</label>
                            <textarea name="description" class="form-control" rows="5" style="border-radius:8px;" required>{{ $assignment->description }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;">Maximum Marks *</label>
                                <input type="number" name="max_marks" value="{{ $assignment->max_marks }}" class="form-control" min="1" style="border-radius:8px;" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;">Due Date & Time *</label>
                                <input type="datetime-local" name="due_date" value="{{ date('Y-m-d\TH:i', strtotime($assignment->due_date)) }}" class="form-control" style="border-radius:8px;" required>
                            </div>
                        </div>

                        @if($assignment->file_path)
                        <div class="p-3 bg-light mb-3" style="border-radius:8px;">
                            <p style="margin:0; font-size:13px; font-weight:600;">Current Attachment:</p>
                            <a href="{{ asset($assignment->file_path) }}" target="_blank"><i class="ti-file"></i> {{ basename($assignment->file_path) }}</a>
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label" style="font-weight:600;">Replace File (optional)</label>
                            <input type="file" name="file" class="form-control-file">
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('assignments.index') }}" class="text-muted"><i class="ti-angle-left mr-1"></i> Cancel</a>
                            <button type="submit" class="primary-btn fix-gr-bg" style="border-radius:25px; padding:10px 32px;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
