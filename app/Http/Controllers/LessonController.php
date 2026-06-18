<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    /**
     * Stream an uploaded video file securely.
     * The real storage path is never exposed to the browser.
     */
    public function stream(Course $course, Lesson $lesson): StreamedResponse
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        // Access control: must be enrolled or lesson must be a preview
        $user = auth()->user();
        $isEnrolled = $user ? Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists() : false;

        if (! $lesson->is_preview && ! $isEnrolled) {
            abort(403, 'Not enrolled in this course.');
        }

        // Must have an uploaded file
        if (! $lesson->video_file_path || ! Storage::disk('local')->exists($lesson->video_file_path)) {
            abort(404, 'Video file not found.');
        }

        $path     = Storage::disk('local')->path($lesson->video_file_path);
        $size     = filesize($path);
        $mimeType = mime_content_type($path) ?: 'video/mp4';

        // Handle HTTP Range requests (needed for video seeking)
        $start  = 0;
        $end    = $size - 1;
        $status = 200;

        $headers = [
            'Content-Type'              => $mimeType,
            'Accept-Ranges'             => 'bytes',
            'Cache-Control'             => 'no-store, no-cache',
            // Prevent download prompt in browser
            'Content-Disposition'       => 'inline',
            // Block embedding in other sites
            'X-Frame-Options'           => 'SAMEORIGIN',
            'X-Content-Type-Options'    => 'nosniff',
        ];

        if (request()->hasHeader('Range')) {
            $range = request()->header('Range');
            preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);

            $start  = (int) $matches[1];
            $end    = isset($matches[2]) && $matches[2] !== '' ? (int) $matches[2] : $size - 1;
            $end    = min($end, $size - 1);
            $status = 206; // Partial Content

            $headers['Content-Range']  = "bytes {$start}-{$end}/{$size}";
            $headers['Content-Length'] = $end - $start + 1;
        } else {
            $headers['Content-Length'] = $size;
        }

        return response()->stream(function () use ($path, $start, $end) {
            $fp = fopen($path, 'rb');
            fseek($fp, $start);
            $remaining = $end - $start + 1;
            $chunkSize = 1024 * 256; // 256 KB chunks

            while ($remaining > 0 && ! feof($fp)) {
                $read = min($chunkSize, $remaining);
                echo fread($fp, $read);
                $remaining -= $read;
                flush();
            }

            fclose($fp);
        }, $status, $headers);
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

        if ($request->progress > $enrollment->progress) {
            $enrollment->progress = $request->progress;
        }

        $enrollment->last_lesson_id = $lesson->id;
        $enrollment->save();

        return response()->json(['ok' => true]);
    }
}
