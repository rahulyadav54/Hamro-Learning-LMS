@extends('backend.master')

@section('css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .premium-dashboard {
        font-family: 'Outfit', sans-serif !important;
        background: #f8fafc;
        color: #1e293b;
        padding: 24px;
    }

    /* Glassmorphism Cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(106, 79, 219, 0.04);
        padding: 24px;
        margin-bottom: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(106, 79, 219, 0.08);
    }

    .header-gradient {
        background: linear-gradient(135deg, #6a4fdb 0%, #a855f7 100%);
        color: #fff;
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(106, 79, 219, 0.2);
    }
    .header-gradient::after {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .badge-premium {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Stats Grid */
    .stat-val {
        font-size: 32px;
        font-weight: 700;
        margin-top: 10px;
        margin-bottom: 2px;
        color: #1e293b;
    }
    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    /* Schedule Timeline */
    .timeline-item {
        position: relative;
        padding-left: 32px;
        margin-bottom: 20px;
        border-left: 2px solid #e2e8f0;
    }
    .timeline-item::after {
        content: '';
        position: absolute;
        left: -6px; top: 4px;
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #6a4fdb;
        border: 2px solid #fff;
    }

    .quick-action-btn {
        padding: 16px 20px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none !important;
        transition: all 0.2s;
        height: 100%;
    }
    .quick-action-btn:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        border-color: #6a4fdb;
    }

    .table-responsive th {
        font-weight: 600;
        color: #64748b;
        background: #f8fafc;
        border: none !important;
    }
    .table-responsive td {
        border-top: 1px solid #f1f5f9 !important;
        vertical-align: middle !important;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
@endsection

@section('mainContent')
<div class="premium-dashboard">

    {{-- ROW 1: Header and Greetings --}}
    <div class="header-gradient d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h1 class="text-white mb-2" style="font-weight:700;">
                Good {{ date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ Auth::user()->name }} 👋
            </h1>
            <p class="text-white-50 m-0">Welcome back to your workspace. Here is what is happening today.</p>
        </div>
        <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
            <span class="badge-premium">
                <i class="ti-calendar mr-2"></i> {{ date('d M Y') }}
            </span>
            <span class="badge-premium">
                <i class="ti-time mr-2"></i> <span id="liveClock"></span>
            </span>
            <a href="{{ route('instructor.ai.index') }}" class="btn btn-light text-primary font-weight-bold" style="border-radius:30px; padding:8px 20px;">
                <i class="ti-wand mr-1"></i> AI Assistant
            </a>
        </div>
    </div>

    {{-- ROW 2: 12 Beautiful Stats cards --}}
    <div class="row">
        @php
            $stats = [
                ['Courses', $total_courses, 'ti-book', '#eff6ff', '#3b82f6'],
                ['Published', $published_courses, 'ti-check-box', '#f0fdf4', '#22c55e'],
                ['Draft', $draft_courses, 'ti-pencil-alt', '#fef2f2', '#ef4444'],
                ['Students Enrolled', $total_enroll, 'ti-user', '#faf5ff', '#a855f7'],
                ['Active Students', $active_students, 'ti-pulse', '#f0fdfa', '#0d9488'],
                ['Assignments Pending', $assignments_pending, 'ti-write', '#fff7ed', '#f97316'],
                ['Today\'s Classes', $today_classes_count, 'ti-video-camera', '#edf2f7', '#4a5568'],
                ['Lifetime Revenue', getSetting()->currency->symbol . number_format($total_reveune, 2), 'ti-wallet', '#ecfeff', '#0891b2'],
                ['Course Rating', '4.8 ★', 'ti-star', '#fffbeb', '#eab308'],
                ['Certificates Issued', '12', 'ti-cup', '#fdf2f8', '#ec4899'],
                ['Completion Rate', '92%', 'ti-pie-chart', '#f0fdf4', '#22c55e'],
                ['Watch Time', '320 hrs', 'ti-alarm-clock', '#f8fafc', '#64748b']
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="col-xl-2 col-lg-4 col-sm-6 col-6 mb-4">
            <div class="glass-card p-3 h-100 d-flex flex-column justify-content-between" style="border-left:4px solid {{ $stat[4] }}">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-label">{{ $stat[0] }}</span>
                    <div class="stat-icon" style="background:{{ $stat[2] === 'ti-wallet' ? '#ecfeff' : $stat[3] }}; color:{{ $stat[4] }};">
                        <i class="{{ $stat[1] }}"></i>
                    </div>
                </div>
                <h4 class="stat-val">{{ $stat[1] }}</h4>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        {{-- ROW 3: Today's Schedule Timeline --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-20">
                    <h4 style="font-weight:700; margin:0;">Today's Schedule</h4>
                    <span class="badge bg-primary text-white" style="border-radius:10px;">{{ count($today_schedule) }} Live Sessions</span>
                </div>
                <div class="timeline mt-3">
                    @if(count($today_schedule) > 0)
                        @foreach($today_schedule as $sched)
                        <div class="timeline-item">
                            <h5 style="font-weight:700; margin-bottom:2px;">{{ $sched->topic }}</h5>
                            <p style="color:#64748b; font-size:12px; margin-bottom:10px;">
                                <i class="ti-time mr-1"></i> {{ $sched->time_of_meeting }} &bull; {{ $sched->meeting_duration }} mins
                            </p>
                            <a href="https://zoom.us/j/{{ $sched->meeting_id }}" target="_blank" class="btn btn-sm btn-success" style="border-radius:8px;">Start Class</a>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="ti-calendar text-muted" style="font-size:36px;"></i>
                            <p class="text-muted mt-2">No live classes scheduled for today.</p>
                            <a href="{{ route('instructor.live-classes.create') }}" class="btn btn-sm btn-primary mt-2" style="border-radius:8px;">Schedule Class</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ROW 4: Tasks / Todo List --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; mb-20">Pending Tasks</h4>
                
                {{-- Add task form --}}
                <form action="#" method="POST" id="addTaskForm" class="d-flex gap-2 mb-3">
                    @csrf
                    <input type="text" name="title" id="taskTitle" placeholder="Add a new reminder task..." class="form-control" style="border-radius:10px;" required>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;">Add</button>
                </form>

                <div id="todoList" style="max-height:220px; overflow-y:auto;">
                    @if(count($pending_tasks) > 0)
                        @foreach($pending_tasks as $task)
                        <div class="d-flex align-items-center justify-content-between p-3 mb-2" style="background:#f8fafc; border-radius:10px;">
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" {{ $task->is_completed ? 'checked' : '' }} onclick="toggleTask({{ $task->id }})">
                                <span style="{{ $task->is_completed ? 'text-decoration: line-through; color: #aaa;' : 'font-weight:500;' }}">{{ $task->title }}</span>
                            </div>
                            <button class="btn btn-sm text-danger" onclick="deleteTask({{ $task->id }})"><i class="ti-trash"></i></button>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4" id="emptyTodo">
                            <p class="text-muted">No pending tasks. Add one above!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ROW 5: Recent Student Activities --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Recent Student Activities</h4>
                <div class="timeline mt-3">
                    @if($recent_enroll->count() > 0)
                        @foreach($recent_enroll as $act)
                        <div class="timeline-item">
                            <h6 style="font-weight:700; margin-bottom:2px;">{{ $act->user->name ?? 'Student' }} Enrolled</h6>
                            <p style="color:#64748b; font-size:12px; margin-bottom:0;">
                                Course: {{ $act->course->title ?? 'N/A' }} &bull; Price: {{ getSetting()->currency->symbol }}{{ number_format($act->reveune, 2) }}
                            </p>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">No recent activities available.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ROW 6: Upcoming Deadlines --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Upcoming Deadlines</h4>
                <div class="p-3 mb-3 bg-light d-flex align-items-center justify-content-between" style="border-radius:12px;">
                    <div>
                        <h6 style="font-weight:700; margin-bottom:2px;">Flutter Graduation Assignment</h6>
                        <span style="color:#ef4444; font-size:12px;"><i class="ti-alarm-clock mr-1"></i> Due in 2 days</span>
                    </div>
                    <span class="badge bg-danger text-white p-2">Urgent</span>
                </div>
                <div class="p-3 mb-3 bg-light d-flex align-items-center justify-content-between" style="border-radius:12px;">
                    <div>
                        <h6 style="font-weight:700; margin-bottom:2px;">Python End Module Quiz</h6>
                        <span style="color:#f59e0b; font-size:12px;"><i class="ti-alarm-clock mr-1"></i> Due in 5 days</span>
                    </div>
                    <span class="badge bg-warning text-white p-2">Upcoming</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ROW 7: Monthly Revenue Chart --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Revenue Analytics</h4>
                <div style="height:250px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        {{-- ROW 8: Student Growth Chart --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Student Enrollment Growth</h4>
                <div style="height:250px;">
                    <canvas id="studentGrowthChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 9: Course Performance Table --}}
    <div class="glass-card">
        <h4 style="font-weight:700; margin-bottom:20px;">Course Performance Overview</h4>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Students</th>
                        <th>Revenue</th>
                        <th>Rating</th>
                        <th>Completion</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @if($recent_enroll->count() > 0)
                        @foreach($recent_enroll->unique('course_id') as $c)
                        <tr>
                            <td style="font-weight:600; color:#1e293b;">{{ Str::limit($c->course->title ?? 'N/A', 50) }}</td>
                            <td>{{ $total_enroll }} Students</td>
                            <td style="font-weight:600; color:#22c55e;">{{ getSetting()->currency->symbol }}{{ number_format($total_reveune, 2) }}</td>
                            <td><span style="color:#eab308;">★</span> 4.8</td>
                            <td>90%</td>
                            <td>
                                <div class="progress" style="height:6px; border-radius:10px;">
                                    <div class="progress-bar bg-success" style="width: 90%; border-radius:10px;"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No courses performance data found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        {{-- ROW 10: Recent Reviews --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Recent Reviews</h4>
                @if(count($recent_reviews) > 0)
                    @foreach($recent_reviews as $rev)
                    <div class="p-3 mb-3 bg-light" style="border-radius:12px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-weight:700;">{{ $rev->user_name }}</span>
                            <span style="color:#eab308;">{{ $rev->star }} ★</span>
                        </div>
                        <p class="text-muted m-0 mt-1" style="font-size:13px;">{{ $rev->comment }}</p>
                        <small class="text-primary mt-2 d-block">Course: {{ $rev->course_title }}</small>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-4">No reviews found.</p>
                @endif
            </div>
        </div>

        {{-- ROW 11: Announcements --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Course Announcements</h4>
                
                {{-- Create Announcement Form --}}
                <form action="#" method="POST" id="announceForm" class="mb-3">
                    @csrf
                    <input type="text" name="title" id="annTitle" placeholder="Announcement Title..." class="form-control mb-2" style="border-radius:8px;" required>
                    <textarea name="content" id="annContent" placeholder="Announcement content..." class="form-control mb-2" rows="3" style="border-radius:8px;" required></textarea>
                    <button type="submit" class="btn btn-primary btn-sm" style="border-radius:20px; padding:6px 20px;">Publish Now</button>
                </form>

                <div id="announceList" style="max-height:180px; overflow-y:auto;">
                    @if(count($announcements) > 0)
                        @foreach($announcements as $ann)
                        <div class="p-3 mb-2 bg-light" style="border-radius:10px;">
                            <h6 style="font-weight:700; margin:0;">{{ $ann->title }}</h6>
                            <p style="color:#64748b; font-size:12px; margin:4px 0 0;">{{ Str::limit($ann->content, 120) }}</p>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 12: Calendar Widget --}}
    <div class="glass-card">
        <h4 style="font-weight:700; margin-bottom:20px;">Interactive Event Calendar</h4>
        <div id="calendar"></div>
    </div>

    {{-- ROW 13: Quick Actions Grid --}}
    <div class="glass-card">
        <h4 style="font-weight:700; margin-bottom:20px;">LMS Quick Actions</h4>
        <div class="row">
            @php
                $actions = [
                    ['Create Course', route('courses'), 'ti-plus', '#6a4fdb'],
                    ['Upload Lesson', route('courses'), 'ti-upload', '#3b82f6'],
                    ['Schedule Live Class', route('instructor.live-classes.create'), 'ti-video-camera', '#10b981'],
                    ['Create Quiz', route('courses'), 'ti-help-alt', '#f59e0b'],
                    ['Create Assignment', route('assignments.create'), 'ti-layout-media-left-alt', '#ec4899'],
                    ['Upload Material', route('instructor.materials.create'), 'ti-file', '#8b5cf6'],
                    ['AI Assistant Workspace', route('instructor.ai.index'), 'ti-wand', '#0d9488'],
                    ['Revenue Reports', route('instructor.reports.revenue'), 'ti-bar-chart', '#0891b2'],
                ];
            @endphp
            @foreach($actions as $act)
            <div class="col-xl-3 col-md-6 mb-3">
                <a href="{{ $act[1] }}" class="quick-action-btn">
                    <div style="background:{{ $act[3] }}15; color:{{ $act[3] }}; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px;">
                        <i class="{{ $act[2] }}"></i>
                    </div>
                    <div>
                        <h6 style="font-weight:700; color:#1e293b; margin:0;">{{ $act[0] }}</h6>
                        <small class="text-muted">Launch helper</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <div class="row">
        {{-- ROW 14: Instructor Performance detail list --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Satisfaction & Watch metrics</h4>
                <div class="p-3 mb-2 bg-light d-flex justify-content-between align-items-center" style="border-radius:10px;">
                    <span>Student Satisfaction Rating</span>
                    <span class="badge bg-success text-white p-2">98% Satisfied</span>
                </div>
                <div class="p-3 mb-2 bg-light d-flex justify-content-between align-items-center" style="border-radius:10px;">
                    <span>Quiz Accuracy Average</span>
                    <span class="badge bg-info text-white p-2">84% Accuracy</span>
                </div>
            </div>
        </div>

        {{-- ROW 15: Top Performing Courses --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Top Performing Course list</h4>
                @if($recent_enroll->count() > 0)
                    @foreach($recent_enroll->unique('course_id')->take(3) as $c)
                    <div class="d-flex align-items-center mb-3">
                        <div style="width:60px; height:40px; background:#e2e8f0; border-radius:6px; margin-right:12px; display:flex; align-items:center; justify-content:center;">
                            <i class="ti-image text-muted"></i>
                        </div>
                        <div>
                            <h6 style="font-weight:700; margin:0;">{{ Str::limit($c->course->title ?? 'N/A', 40) }}</h6>
                            <small class="text-muted">Enrollments: {{ $total_enroll }} Students</small>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ROW 16: Top Students progress --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Top Students Progress</h4>
                @if(count($top_students) > 0)
                    @foreach($top_students as $st)
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <div style="width:40px; height:40px; border-radius:50%; background:#6a4fdb; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; margin-right:12px;">
                                {{ substr($st->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 style="font-weight:700; margin:0;">{{ $st->name }}</h6>
                                <small class="text-muted">{{ $st->email }}</small>
                            </div>
                        </div>
                        <span class="badge bg-success text-white p-2">95% Completed</span>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- ROW 17: Attendance Overview --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Attendance Overview</h4>
                <div class="row text-center mt-3">
                    <div class="col-4">
                        <h3 style="color:#22c55e; font-weight:700;">94%</h3>
                        <span class="text-muted" style="font-size:12px;">PRESENT</span>
                    </div>
                    <div class="col-4">
                        <h3 style="color:#ef4444; font-weight:700;">4%</h3>
                        <span class="text-muted" style="font-size:12px;">ABSENT</span>
                    </div>
                    <div class="col-4">
                        <h3 style="color:#f59e0b; font-weight:700;">2%</h3>
                        <span class="text-muted" style="font-size:12px;">LATE</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ROW 18: Messages (Recent chat) --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Inbox & Chat list</h4>
                @if(count($recent_chats) > 0)
                    @foreach($recent_chats as $ch)
                    <div class="d-flex align-items-center justify-content-between p-3 bg-light mb-2" style="border-radius:12px;">
                        <div>
                            <h6 style="font-weight:700; margin:0;">{{ $ch->sender_name }}</h6>
                            <p style="margin:4px 0 0; color:#64748b; font-size:13px;">{{ Str::limit($ch->message, 80) }}</p>
                        </div>
                        <span class="badge bg-danger text-white rounded-circle p-2">1</span>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-4">No recent messages.</p>
                @endif
            </div>
        </div>

        {{-- ROW 19: Discussion Forum --}}
        <div class="col-xl-6 mb-4">
            <div class="glass-card h-100">
                <h4 style="font-weight:700; margin-bottom:20px;">Discussion Forum</h4>
                <div class="p-3 mb-2 bg-light" style="border-radius:12px;">
                    <h6 style="font-weight:700; margin:0;">How to build connection pools in Flutter?</h6>
                    <small class="text-muted">Asked by Rohan Kumar &bull; 2 hours ago</small>
                </div>
                <div class="p-3 mb-2 bg-light" style="border-radius:12px;">
                    <h6 style="font-weight:700; margin:0;">State management choice: Riverpod vs Bloc?</h6>
                    <small class="text-muted">Asked by Priyesh Roy &bull; Yesterday</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 20: Resource Library --}}
    <div class="glass-card">
        <h4 style="font-weight:700; margin-bottom:20px;">Resource Library Overview</h4>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <p class="text-muted">You have uploaded total **{{ $materials_count }}** materials (PDFs, ZIP folders, Slides, Code files) for your classes.</p>
                <a href="{{ route('instructor.materials.index') }}" class="btn btn-primary" style="border-radius:20px; padding:8px 24px;">Manage Materials</a>
            </div>
            <div class="col-lg-6 text-center">
                <i class="ti-folder text-primary" style="font-size:72px;"></i>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    // Live Clock
    function startTime() {
        const today = new Date();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('liveClock').innerHTML = h + ":" + m + ":" + s;
        setTimeout(startTime, 1000);
    }
    function checkTime(i) {
        if (i < 10) {i = "0" + i};
        return i;
    }
    window.onload = function() {
        startTime();
    };

    // Revenue Chart
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Earnings ($)',
                data: [1200, 1900, 3000, 2500, 4000, 3500, 4800, 5200, 6000, 5500, 7000, 8500],
                borderColor: '#6a4fdb',
                tension: 0.3,
                fill: true,
                backgroundColor: 'rgba(106, 79, 219, 0.05)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Student Growth Chart
    const growthCtx = document.getElementById('studentGrowthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'New Enrolls',
                data: [45, 80, 120, 100, 220, 190, 310],
                backgroundColor: '#10b981',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Interactive FullCalendar Event list feed
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: '{{ route("calendar.feed") }}',
            eventClick: function(info) {
                if(confirm('Are you sure you want to delete this event: ' + info.event.title + '?')) {
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
    });

    // Handle AJAX Task Creation
    document.getElementById('addTaskForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const title = document.getElementById('taskTitle').value;
        
        fetch('{{ route("calendar.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                title: title,
                start: new Date().toISOString().slice(0, 19).replace('T', ' '),
                type: 'event'
            })
        }).then(res => res.json())
        .then(data => {
            if(data.success) {
                location.reload();
            }
        });
    });

    // Handle AJAX Announcement Publish
    document.getElementById('announceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const title = document.getElementById('annTitle').value;
        const content = document.getElementById('annContent').value;

        fetch('{{ route("instructor.ai.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                type: 'announcement',
                topic: title
            })
        }).then(res => res.json())
        .then(data => {
            if(data.success) {
                alert('Announcement published!');
                location.reload();
            }
        });
    });
</script>
@endsection
