<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $user = auth()->user();
        $isEnrolled = $user ? Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists() : false;

        if (! $lesson->is_preview && ! $isEnrolled) {
            return redirect()->route('curses')->with('error', __('You must be enrolled to view this lesson.'));
        }

        $lessons = $course->lessons()->orderBy('order')->get();

        return view('lesson.show', compact('course', 'lesson', 'lessons', 'isEnrolled'));
    }

    public function updateProgress(Request $request, Course $course, Lesson $lesson)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $user = auth()->user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->first();

        if (! $enrollment) {
            return response()->json(['error' => 'Not enrolled'], 403);
        }

        // Only update if the new progress is greater than current, or to mark as complete
        if ($request->progress > $enrollment->progress) {
            $enrollment->progress = $request->progress;
        }

        $enrollment->last_lesson_id = $lesson->id;
        $enrollment->save();

        return response()->json(['ok' => true]);
    }
}
