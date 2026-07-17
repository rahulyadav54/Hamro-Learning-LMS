@extends('backend.master')

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Assignments</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="#">Assignments</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="white-box" style="border-radius:12px;">
                    <div class="p-20 d-flex justify-content-between align-items-center" style="border-bottom:1px solid #f0f0f0;">
                        <h4 style="margin:0; font-weight:600; color:#333;">All Assignments</h4>
                        <a href="{{ route('assignments.create') }}" class="primary-btn fix-gr-bg" style="border-radius:25px; padding:8px 20px;">
                            <i class="ti-plus mr-1"></i> Create Assignment
                        </a>
                    </div>

                    @if($assignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:14px;">
                            <thead style="background:#fafafa;">
                                <tr>
                                    <th>Course</th>
                                    <th>Title</th>
                                    <th>Max Marks</th>
                                    <th>Due Date</th>
                                    <th>Submissions</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                <tr style="border-top:1px solid #f0f0f0;">
                                    <td><span style="color:#6a4fdb; font-weight:600;">{{ $assignment->course->title ?? 'N/A' }}</span></td>
                                    <td>
                                        <p style="margin:0; font-weight:600;">{{ $assignment->title }}</p>
                                        @if($assignment->file_path)
                                        <small><a href="{{ asset($assignment->file_path) }}" target="_blank"><i class="ti-clip"></i> Attachment</a></small>
                                        @endif
                                    </td>
                                    <td>{{ $assignment->max_marks }}</td>
                                    <td><span style="color:#ef4444; font-weight:500;">{{ $assignment->due_date->format('d M Y, h:i A') }}</span></td>
                                    <td>
                                        <a href="{{ route('assignments.submissions', $assignment->id) }}" class="btn btn-sm btn-info text-white" style="border-radius:20px; padding:3px 12px; font-size:12px;">
                                            {{ $assignment->submissions->count() }} Submissions
                                        </a>
                                    </td>
                                    <td style="text-align:right;">
                                        <a href="{{ route('assignments.edit', $assignment->id) }}" class="btn btn-sm btn-primary" style="border-radius:6px;"><i class="ti-pencil"></i></a>
                                        <a href="{{ route('assignments.delete', $assignment->id) }}" onclick="return confirm('Delete this assignment?')" class="btn btn-sm btn-danger" style="border-radius:6px;"><i class="ti-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-20">
                        {{ $assignments->links() }}
                    </div>
                    @else
                    <div class="text-center p-60">
                        <i class="ti-write text-muted" style="font-size:48px;"></i>
                        <h4 class="mt-3">No assignments yet</h4>
                        <p class="text-muted">Create assignments to challenge and assess your students.</p>
                        <a href="{{ route('assignments.create') }}" class="primary-btn fix-gr-bg mt-2" style="border-radius:25px;">Create Assignment</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
