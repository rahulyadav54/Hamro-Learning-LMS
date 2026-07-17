<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        return view('backend.instructor.calendar.index');
    }

    public function feed()
    {
        $events = CalendarEvent::where('user_id', Auth::id())->get();

        $formatted = [];
        foreach ($events as $event) {
            $formatted[] = [
                'id'          => $event->id,
                'title'       => $event->title,
                'start'       => $event->start->toIso8601String(),
                'end'         => $event->end ? $event->end->toIso8601String() : null,
                'color'       => $event->color,
                'description' => $event->description,
                'type'        => $event->type,
            ];
        }

        return response()->json($formatted);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required',
            'type'  => 'required',
        ]);

        try {
            $colors = [
                'class'      => '#3b82f6', // blue
                'quiz'       => '#f59e0b', // orange
                'assignment' => '#10b981', // green
                'meeting'    => '#8b5cf6', // purple
                'event'      => '#6b7280', // grey
            ];

            $event = CalendarEvent::create([
                'user_id'     => Auth::id(),
                'title'       => $request->title,
                'description' => $request->description,
                'start'       => $request->start,
                'end'         => $request->end,
                'type'        => $request->type,
                'color'       => $colors[$request->type] ?? '#6a4fdb',
            ]);

            return response()->json(['success' => true, 'event' => $event]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            CalendarEvent::where('id', $id)->where('user_id', Auth::id())->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
