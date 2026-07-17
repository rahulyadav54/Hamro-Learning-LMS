@extends('backend.master')

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Student Enrollment Analytics</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="#">Reports</a>
                <a href="#">Students</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="white-box" style="border-radius:12px;">
                    <h4 class="mb-30" style="font-weight:600;">Student Enrollments & Progress Log</h4>

                    @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:14px;">
                            <thead style="background:#fafafa;">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email Address</th>
                                    <th>Enrolled Course</th>
                                    <th>Purchase Date</th>
                                    <th>Revenue Share</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                <tr style="border-top:1px solid #f0f0f0;">
                                    <td style="font-weight:600; color:#333;">{{ $st->name }}</td>
                                    <td>{{ $st->email }}</td>
                                    <td style="font-weight:500; color:#6a4fdb;">{{ $st->course_title }}</td>
                                    <td>{{ date('d M Y, h:i A', strtotime($st->created_at)) }}</td>
                                    <td style="font-weight:600; color:#22c55e;">{{ getSetting()->currency->symbol }}{{ number_format($st->reveune, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-20">
                        {{ $students->links() }}
                    </div>
                    @else
                    <div class="text-center p-60">
                        <i class="ti-user text-muted" style="font-size:48px;"></i>
                        <h4 class="mt-3">No student enrollments found</h4>
                        <p class="text-muted">Once students register or purchase your courses, they will appear here.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
