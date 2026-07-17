<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\Payment\Entities\Checkout;

class StudentAppController extends Controller
{
    public function dashboard(Request $request)
    {
        try {
            $user = $request->user();

            $courses = CourseEnrolled::where('user_id', $user->id)
                ->with('course.user', 'course.class', 'course.lessons', 'course.completeLessons')
                ->latest()
                ->get();

            $liveClasses = $courses->filter(function ($enrollment) {
                return !empty($enrollment->course) && !empty($enrollment->course->class_id);
            })->values();

            $checkouts = Checkout::where('user_id', $user->id)
                ->where('status', 1)
                ->with('billing', 'courses.course')
                ->latest()
                ->take(5)
                ->get();

            $pendingPayments = Checkout::where('user_id', $user->id)
                ->where('status', 0)
                ->count();

            $nextLiveClass = $liveClasses->first();
            $nextLiveClassData = null;

            if ($nextLiveClass && $nextLiveClass->course && $nextLiveClass->course->class) {
                $class = $nextLiveClass->course->class;
                $nextLiveClassData = [
                    'course_id' => $nextLiveClass->course->id,
                    'course_title' => $nextLiveClass->course->title,
                    'class_title' => $class->title ?? $nextLiveClass->course->title,
                    'start_date' => $class->start_date,
                    'end_date' => $class->end_date,
                    'time' => $class->time,
                    'instructor_name' => optional($nextLiveClass->course->user)->name,
                ];
            }

            $recentCourses = $courses->take(4)->map(function ($enrollment) {
                $course = $enrollment->course;
                $progress = 0;

                if ($course && count($course->lessons) > 0) {
                    $completed = $course->completeLessons->where('status', 1)->count();
                    $progress = ceil(($completed / count($course->lessons)) * 100);
                }

                return [
                    'course_id' => $course->id ?? null,
                    'title' => $course->title ?? '',
                    'thumbnail' => $course->thumbnail ?? '',
                    'duration' => $course->duration ?? '',
                    'instructor_name' => optional($course->user)->name,
                    'price' => (float) $enrollment->purchase_price,
                    'progress' => $progress,
                ];
            })->values();

            $recentPayments = $checkouts->map(function ($checkout) {
                $courseTitles = $checkout->courses->map(function ($item) {
                    return optional($item->course)->title;
                })->filter()->values();

                return [
                    'id' => $checkout->id,
                    'tracking' => $checkout->tracking,
                    'title' => $courseTitles->isNotEmpty() ? $courseTitles->implode(', ') : 'Payment',
                    'amount' => (float) ($checkout->purchase_price ?? $checkout->price ?? 0),
                    'status' => $checkout->status ? 'Paid' : 'Pending',
                    'payment_method' => $checkout->payment_method,
                    'date' => $checkout->dateFormat,
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'image' => $user->image,
                    ],
                    'stats' => [
                        'enrolled_courses' => $courses->count(),
                        'live_classes' => $liveClasses->count(),
                        'total_spent' => (float) $courses->sum('purchase_price'),
                        'pending_payments' => $pendingPayments,
                    ],
                    'next_live_class' => $nextLiveClassData,
                    'recent_courses' => $recentCourses,
                    'recent_payments' => $recentPayments,
                ],
                'message' => 'Dashboard loaded successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function liveClasses(Request $request)
    {
        try {
            $courses = CourseEnrolled::where('user_id', $request->user()->id)
                ->whereHas('course', function ($query) {
                    $query->whereNotNull('class_id');
                })
                ->with('course.user', 'course.class')
                ->latest()
                ->get();

            $data = $courses->map(function ($enrollment) {
                $course = $enrollment->course;
                $class = $course ? $course->class : null;

                return [
                    'course_id' => $course->id ?? null,
                    'course_title' => $course->title ?? '',
                    'thumbnail' => $course->thumbnail ?? '',
                    'instructor_name' => optional($course->user)->name,
                    'class_title' => $class->title ?? ($course->title ?? ''),
                    'start_date' => $class->start_date ?? null,
                    'end_date' => $class->end_date ?? null,
                    'time' => $class->time ?? null,
                    'status' => $class ? 'Scheduled' : 'Unavailable',
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Live classes loaded successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function purchases(Request $request)
    {
        try {
            $checkouts = Checkout::where('user_id', $request->user()->id)
                ->where('status', 1)
                ->with('billing', 'courses.course')
                ->latest()
                ->get();

            $data = $checkouts->map(function ($checkout) {
                $items = $checkout->courses->map(function ($item) {
                    return [
                        'course_id' => optional($item->course)->id,
                        'title' => optional($item->course)->title,
                        'thumbnail' => optional($item->course)->thumbnail,
                    ];
                })->filter(function ($item) {
                    return !empty($item['course_id']);
                })->values();

                return [
                    'id' => $checkout->id,
                    'tracking' => $checkout->tracking,
                    'title' => $items->pluck('title')->filter()->implode(', '),
                    'amount' => (float) ($checkout->purchase_price ?? $checkout->price ?? 0),
                    'discount' => (float) ($checkout->discount ?? 0),
                    'payment_method' => $checkout->payment_method,
                    'status' => $checkout->status ? 'Paid' : 'Pending',
                    'date' => $checkout->dateFormat,
                    'billing_name' => trim((optional($checkout->billing)->first_name ?? '') . ' ' . (optional($checkout->billing)->last_name ?? '')),
                    'courses' => $items,
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Payment history loaded successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
