<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Student\StudentController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\Notification;

class InstructorController extends StudentController
{
    public function dashboard()
    {
        $user = Auth::user();

        // 1. Course Stats
        $data['total_courses'] = \Modules\CourseSetting\Entities\Course::where('user_id', $user->id)->count();
        $data['published_courses'] = \Modules\CourseSetting\Entities\Course::where('user_id', $user->id)->where('status', 1)->count();
        $data['draft_courses'] = \Modules\CourseSetting\Entities\Course::where('user_id', $user->id)->where('status', 0)->count();

        // 2. Student & Enrollment Stats
        $data['total_enroll'] = CourseEnrolled::whereHas('course', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $data['active_students'] = $data['total_enroll']; // Active is equivalent to total enrolled on this LMS

        // 3. Earnings & Payout Stats
        $data['total_reveune'] = CourseEnrolled::whereHas('course', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->sum('reveune');

        // 4. Assignments & Quizzes
        $data['assignments_pending'] = DB::table('assignments')
            ->join('assignment_submissions', 'assignments.id', '=', 'assignment_submissions.assignment_id')
            ->where('assignments.instructor_id', $user->id)
            ->where('assignment_submissions.status', 0)
            ->count();

        $data['today_classes_count'] = DB::table('zoom_meetings')
            ->where('instructor_id', $user->id)
            ->where('date_of_meeting', date('Y-m-d'))
            ->count();

        // 5. Recent Enrollments
        $data['recent_enroll'] = CourseEnrolled::whereHas('course', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('course', 'user')->latest()->take(5)->get();

        // 6. Today's Schedule timeline
        $data['today_schedule'] = DB::table('zoom_meetings')
            ->where('instructor_id', $user->id)
            ->where('date_of_meeting', date('Y-m-d'))
            ->orderBy('time_of_meeting', 'asc')
            ->get();

        // 7. Tasks/Todo list
        $data['pending_tasks'] = DB::table('instructor_todo')
            ->where('user_id', $user->id)
            ->get();

        // 8. Student Reviews
        $data['recent_reviews'] = DB::table('course_reveiws')
            ->join('users', 'course_reveiws.user_id', '=', 'users.id')
            ->join('courses', 'course_reveiws.course_id', '=', 'courses.id')
            ->select('course_reveiws.*', 'users.name as user_name', 'users.image as user_image', 'courses.title as course_title')
            ->where('courses.user_id', $user->id)
            ->orderBy('course_reveiws.created_at', 'desc')
            ->take(5)
            ->get();

        // 9. Announcements
        $data['announcements'] = DB::table('instructor_announcements')
            ->where('instructor_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // 10. Top Students list
        $data['top_students'] = DB::table('course_enrolleds')
            ->join('users', 'course_enrolleds.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.email', 'users.image', DB::raw('count(course_enrolleds.id) as course_count'))
            ->whereIn('course_enrolleds.course_id', function($q) use ($user) {
                $q->select('id')->from('courses')->where('user_id', $user->id);
            })
            ->groupBy('users.id', 'users.name', 'users.email', 'users.image')
            ->orderBy('course_count', 'desc')
            ->take(5)
            ->get();

        // 11. Resource Library files count
        $data['materials_count'] = DB::table('instructor_materials')
            ->where('instructor_id', $user->id)
            ->count();

        // 12. Message Chat List
        $data['recent_chats'] = DB::table('messages')
            ->join('users', 'messages.sender_id', '=', 'users.id')
            ->select('messages.*', 'users.name as sender_name', 'users.image as sender_image')
            ->where('messages.reciever_id', $user->id)
            ->latest()
            ->get()
            ->unique('sender_id')
            ->take(5);

        return view('backend.student.index', $data);
    }

    public function index()
    {
        return $this->dashboard();
    }

    public function profile_data()
    {
        return response()->json(Auth::user());
    }

    public function toastrMessages()
    {
        return response()->json(['messages' => []]);
    }

    public function getHistory()
    {
        $payouts = \Modules\Payment\Entities\Withdraw::where('instructor_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('backend.instructor.payout_histpry', compact('payouts'));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        return response()->json($notifications);
    }

    public function notificationComment($id)
    {
        $notification = Notification::where('user_id', Auth::id())->where('course_comment_id', $id)->first();
        if ($notification) {
            $notification->read_status = 1;
            $notification->save();
        }

        return redirect()->back();
    }

    public function notificationReview($id)
    {
        $notification = Notification::where('user_id', Auth::id())->where('course_review_id', $id)->first();
        if ($notification) {
            $notification->read_status = 1;
            $notification->save();
        }

        return redirect()->back();
    }

    public function notificationEnroll($id)
    {
        $notification = Notification::where('user_id', Auth::id())->where('course_enrolled_id', $id)->first();
        if ($notification) {
            $notification->read_status = 1;
            $notification->save();
        }

        return redirect()->back();
    }

    public function users()
    {
        $users = User::where('role_id', 3)->select('id', 'name', 'email')->get();

        return response()->json($users);
    }

    public function messages($id)
    {
        $messages = \Modules\Chat\Entities\Message::where('sender_id', $id)
            ->orWhere('receiver_id', $id)
            ->latest()
            ->take(20)
            ->get();

        return response()->json($messages);
    }

    public function sentMessage(Request $request, $id)
    {
        $message = new \Modules\Chat\Entities\Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $id;
        $message->message = $request->message;
        $message->save();

        return response()->json(['success' => true]);
    }

    public function enrollMonthly()
    {
        $data = CourseEnrolled::whereHas('course', function ($q) {
            $q->where('user_id', Auth::id());
        })
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, count(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        return response()->json($data);
    }

    public function enrollYearly()
    {
        $data = CourseEnrolled::whereHas('course', function ($q) {
            $q->where('user_id', Auth::id());
        })
            ->selectRaw('YEAR(created_at) as year, count(*) as total')
            ->groupBy('year')
            ->pluck('total', 'year');

        return response()->json($data);
    }
}
