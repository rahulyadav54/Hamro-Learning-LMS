<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Assignment;
use App\AssignmentSubmission;
use Modules\CourseSetting\Entities\Course;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AssignmentController extends Controller
{
    public function index()
    {
        $courses = Course::where('user_id', Auth::id())->get();
        $assignments = Assignment::with('course')
            ->where('instructor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('backend.instructor.assignments.index', compact('assignments', 'courses'));
    }

    public function create()
    {
        $courses = Course::where('user_id', Auth::id())->get();
        return view('backend.instructor.assignments.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'   => 'required',
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'max_marks'   => 'required|integer|min:1',
            'due_date'    => 'required',
            'file'        => 'nullable|file|max:20480',
        ]);

        try {
            $filePath = null;
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move('public/uploads/assignments/', $fileName);
                $filePath = 'public/uploads/assignments/' . $fileName;
            }

            Assignment::create([
                'instructor_id' => Auth::id(),
                'course_id'     => $request->course_id,
                'title'         => $request->title,
                'description'   => $request->description,
                'max_marks'     => $request->max_marks,
                'due_date'      => $request->due_date,
                'file_path'     => $filePath,
                'status'        => 1,
            ]);

            Toastr::success('Assignment created successfully!', 'Success');
            return redirect()->route('assignments.index');
        } catch (\Exception $e) {
            Toastr::error('Operation failed: ' . $e->getMessage(), 'Failed');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $assignment = Assignment::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
        $courses = Course::where('user_id', Auth::id())->get();
        return view('backend.instructor.assignments.edit', compact('assignment', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id'   => 'required',
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'max_marks'   => 'required|integer|min:1',
            'due_date'    => 'required',
            'file'        => 'nullable|file|max:20480',
        ]);

        try {
            $assignment = Assignment::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                if ($assignment->file_path && file_exists($assignment->file_path)) {
                    unlink($assignment->file_path);
                }
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move('public/uploads/assignments/', $fileName);
                $assignment->file_path = 'public/uploads/assignments/' . $fileName;
            }

            $assignment->course_id   = $request->course_id;
            $assignment->title       = $request->title;
            $assignment->description = $request->description;
            $assignment->max_marks   = $request->max_marks;
            $assignment->due_date    = $request->due_date;
            $assignment->save();

            Toastr::success('Assignment updated successfully!', 'Success');
            return redirect()->route('assignments.index');
        } catch (\Exception $e) {
            Toastr::error('Operation failed.', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $assignment = Assignment::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
            if ($assignment->file_path && file_exists($assignment->file_path)) {
                unlink($assignment->file_path);
            }
            $assignment->delete();
            Toastr::success('Assignment deleted successfully!', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation failed.', 'Failed');
        }
        return redirect()->back();
    }

    public function submissions($id)
    {
        $assignment = Assignment::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
        $submissions = AssignmentSubmission::with('student')
            ->where('assignment_id', $id)
            ->latest()
            ->get();

        return view('backend.instructor.assignments.submissions', compact('assignment', 'submissions'));
    }

    public function grade(Request $request, $id)
    {
        $request->validate([
            'marks_obtained' => 'required|integer|min:0',
            'feedback'       => 'nullable|string',
        ]);

        try {
            $submission = AssignmentSubmission::findOrFail($id);
            $assignment = Assignment::findOrFail($submission->assignment_id);

            if ($request->marks_obtained > $assignment->max_marks) {
                Toastr::error('Marks obtained cannot exceed max marks (' . $assignment->max_marks . ')', 'Validation Error');
                return redirect()->back();
            }

            $submission->marks_obtained = $request->marks_obtained;
            $submission->feedback       = $request->feedback;
            $submission->status         = 1; // Graded
            $submission->save();

            Toastr::success('Submission graded successfully!', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation failed.', 'Failed');
        }
        return redirect()->back();
    }
}
