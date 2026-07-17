<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Modules\CourseSetting\Entities\Course;

class LiveClassController extends Controller
{
    public function index()
    {
        $courses = Course::where('user_id', Auth::id())->get();

        // Load existing zoom and bbb meetings
        $zoom_meetings = DB::table('zoom_meetings')
            ->where('instructor_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        $bbb_meetings = DB::table('bbb_meetings')
            ->where('instructor_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.instructor.live_classes.index', compact('zoom_meetings', 'bbb_meetings', 'courses'));
    }

    public function create()
    {
        $courses = Course::where('user_id', Auth::id())->get();
        return view('backend.instructor.live_classes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'course_id'   => 'required',
            'host'        => 'required|in:Zoom,GoogleMeet,BBB',
            'start_time'  => 'required',
            'duration'    => 'required|integer',
            'password'    => 'nullable|string',
        ]);

        try {
            $joinUrl = "https://meet.google.com/abc-defg-hij";
            if ($request->host === 'Zoom') {
                $joinUrl = "https://zoom.us/j/" . rand(100000000, 999999999);
            }

            // Insert into virtual_classes or mock Zoom table
            DB::table('zoom_meetings')->insert([
                'class_id'          => $request->course_id,
                'created_by'        => Auth::id(),
                'instructor_id'     => Auth::id(),
                'meeting_id'        => rand(100000000, 999999999),
                'password'          => $request->password ?? '123456',
                'topic'             => $request->title,
                'description'       => $request->description ?? 'Live learning classroom session.',
                'date_of_meeting'   => date('Y-m-d', strtotime($request->start_time)),
                'time_of_meeting'   => date('H:i', strtotime($request->start_time)),
                'meeting_duration'  => $request->duration,
                'status'            => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            Toastr::success('Live Class scheduled successfully!', 'Success');
            return redirect()->route('instructor.live-classes.index');
        } catch (\Exception $e) {
            Toastr::error('Scheduling failed: ' . $e->getMessage(), 'Failed');
            return redirect()->back()->withInput();
        }
    }
}
