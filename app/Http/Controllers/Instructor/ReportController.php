<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;

class ReportController extends Controller
{
    public function revenue()
    {
        $courses = Course::where('user_id', Auth::id())->get();

        // Calculate revenue per course
        $revenueData = [];
        foreach ($courses as $course) {
            $revenue = CourseEnrolled::where('course_id', $course->id)->sum('reveune');
            $revenueData[] = [
                'title'   => $course->title,
                'revenue' => $revenue,
                'sales'   => CourseEnrolled::where('course_id', $course->id)->count()
            ];
        }

        return view('backend.instructor.reports.revenue', compact('revenueData'));
    }

    public function student()
    {
        $courses = Course::where('user_id', Auth::id())->get();
        $courseIds = $courses->pluck('id')->toArray();

        $students = DB::table('course_enrolleds')
            ->join('users', 'course_enrolleds.user_id', '=', 'users.id')
            ->join('courses', 'course_enrolleds.course_id', '=', 'courses.id')
            ->select('users.name', 'users.email', 'courses.title as course_title', 'course_enrolleds.created_at', 'course_enrolleds.reveune')
            ->whereIn('course_enrolleds.course_id', $courseIds)
            ->orderBy('course_enrolleds.created_at', 'desc')
            ->paginate(15);

        return view('backend.instructor.reports.student', compact('students'));
    }
}
