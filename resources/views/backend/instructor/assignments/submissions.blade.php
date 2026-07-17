@extends('backend.master')

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Submissions</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('assignments.index') }}">Assignments</a>
                <a href="#">Submissions</a>
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
                        <div>
                            <h4 style="margin:0; font-weight:600; color:#333;">Submissions for: {{ $assignment->title }}</h4>
                            <span class="text-muted">Maximum Marks: {{ $assignment->max_marks }} &bull; Due Date: {{ $assignment->due_date->format('d M Y') }}</span>
                        </div>
                    </div>

                    @if(count($submissions) > 0)
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:14px;">
                            <thead style="background:#fafafa;">
                                <tr>
                                    <th>Student</th>
                                    <th>Submission Text</th>
                                    <th>Uploaded File</th>
                                    <th>Status</th>
                                    <th>Grade (Marks)</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $sub)
                                <tr style="border-top:1px solid #f0f0f0;">
                                    <td style="font-weight:600;">{{ $sub->student->name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($sub->submission_text, 50) }}</td>
                                    <td>
                                        @if($sub->submission_file)
                                        <a href="{{ asset($sub->submission_file) }}" target="_blank" class="btn btn-sm btn-info text-white" style="border-radius:20px; padding:3px 12px; font-size:12px;">
                                            <i class="ti-download"></i> Download File
                                        </a>
                                        @else
                                        <span class="text-muted">No file</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sub->status == 1)
                                        <span style="background:#d1fae5; color:#065f46; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500;">Graded</span>
                                        @else
                                        <span style="background:#fff7ed; color:#c2410c; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500;">Pending Review</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sub->status == 1)
                                        <strong>{{ $sub->marks_obtained }}</strong> / {{ $assignment->max_marks }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        {{-- Grading Trigger Button --}}
                                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#gradeModal{{ $sub->id }}" style="border-radius:20px; padding:4px 14px; font-size:12px;">
                                            Grade
                                        </button>

                                        {{-- Grading Modal --}}
                                        <div class="modal fade" id="gradeModal{{ $sub->id }}" tabindex="-1" role="dialog" aria-hidden="true" style="text-align:left;">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content" style="border-radius:12px;">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Grade Submission - {{ $sub->student->name }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('submissions.grade', $sub->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label" style="font-weight:600;">Marks Obtained (Max: {{ $assignment->max_marks }}) *</label>
                                                                <input type="number" name="marks_obtained" value="{{ $sub->marks_obtained ?? '' }}" min="0" max="{{ $assignment->max_marks }}" class="form-control" style="border-radius:8px;" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label" style="font-weight:600;">Feedback</label>
                                                                <textarea name="feedback" class="form-control" rows="3" style="border-radius:8px;">{{ $sub->feedback }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:20px;">Close</button>
                                                            <button type="submit" class="btn btn-primary" style="border-radius:20px;">Submit Grade</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center p-60">
                        <i class="ti-file text-muted" style="font-size:48px;"></i>
                        <h4 class="mt-3">No submissions yet</h4>
                        <p class="text-muted">Students have not uploaded any files for this assignment yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
