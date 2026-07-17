@extends('backend.master')

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <h1>Live Classes</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="#">Live Classes</a>
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
                        <h4 style="margin:0; font-weight:600; color:#333;">Schedule and Sessions</h4>
                        <a href="{{ route('instructor.live-classes.create') }}" class="primary-btn fix-gr-bg" style="border-radius:25px; padding:8px 20px;">
                            <i class="ti-video-camera mr-1"></i> Schedule Class
                        </a>
                    </div>

                    <div class="p-20">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="zoom-tab" data-toggle="tab" href="#zoom" role="tab" aria-controls="zoom" aria-selected="true">Zoom Meetings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bbb-tab" data-toggle="tab" href="#bbb" role="tab" aria-controls="bbb" aria-selected="false">BigBlueButton (BBB)</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-4" id="myTabContent">
                            {{-- Zoom meetings --}}
                            <div class="tab-pane fade show active" id="zoom" role="tabpanel" aria-labelledby="zoom-tab">
                                @if(count($zoom_meetings) > 0)
                                <div class="table-responsive">
                                    <table class="table mb-0" style="font-size:14px;">
                                        <thead>
                                            <tr>
                                                <th>Topic</th>
                                                <th>Meeting ID</th>
                                                <th>Start Date</th>
                                                <th>Time</th>
                                                <th>Duration</th>
                                                <th>Password</th>
                                                <th style="text-align:right;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($zoom_meetings as $zm)
                                            <tr style="border-top:1px solid #f0f0f0;">
                                                <td style="font-weight:600;">{{ $zm->topic }}</td>
                                                <td>{{ $zm->meeting_id }}</td>
                                                <td>{{ $zm->date_of_meeting }}</td>
                                                <td>{{ $zm->time_of_meeting }}</td>
                                                <td>{{ $zm->meeting_duration }} mins</td>
                                                <td><code>{{ $zm->password }}</code></td>
                                                <td style="text-align:right;">
                                                    <a href="https://zoom.us/j/{{ $zm->meeting_id }}" target="_blank" class="btn btn-sm btn-success" style="border-radius:20px; padding:4px 14px; font-size:12px;">Start Class</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <p class="text-muted">No scheduled Zoom meetings.</p>
                                </div>
                                @endif
                            </div>

                            {{-- BBB meetings --}}
                            <div class="tab-pane fade" id="bbb" role="tabpanel" aria-labelledby="bbb-tab">
                                @if(count($bbb_meetings) > 0)
                                <div class="table-responsive">
                                    <table class="table mb-0" style="font-size:14px;">
                                        <thead>
                                            <tr>
                                                <th>Meeting ID</th>
                                                <th>Topic</th>
                                                <th>Duration</th>
                                                <th style="text-align:right;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bbb_meetings as $bm)
                                            <tr style="border-top:1px solid #f0f0f0;">
                                                <td>{{ $bm->meeting_id }}</td>
                                                <td style="font-weight:600;">{{ $bm->topic }}</td>
                                                <td>{{ $bm->meeting_duration }} mins</td>
                                                <td style="text-align:right;">
                                                    <a href="#" class="btn btn-sm btn-success" style="border-radius:20px; padding:4px 14px; font-size:12px;">Join Class</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <p class="text-muted">No scheduled BBB meetings.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
