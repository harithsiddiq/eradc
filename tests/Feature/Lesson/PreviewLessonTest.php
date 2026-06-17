<?php

use App\Models\Course;
use App\Models\Lesson;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

test('guest can view preview lesson', function () {
    $course = Course::create([
        'slug' => 'test-course',
        'title' => ['en' => 'Test Course', 'ar' => 'Test Course AR'],
        'description' => ['en' => 'Description', 'ar' => 'Description AR'],
        'is_published' => true,
    ]);

    $lesson = Lesson::create([
        'course_id' => $course->id,
        'slug' => 'preview-lesson',
        'title' => ['en' => 'Preview Lesson', 'ar' => 'Preview Lesson AR'],
        'description' => ['en' => 'Lesson desc', 'ar' => 'Lesson desc AR'],
        'video_url' => 'https://example.com/video.mp4',
        'video_provider' => 'direct',
        'duration_seconds' => 120,
        'order' => 1,
        'is_published' => true,
        'is_preview' => true, // Preview is true
    ]);

    $response = $this->get(route('lesson.show', ['course' => $course->slug, 'lesson' => $lesson->slug]));

    $response->assertStatus(200);
    $response->assertSee('Preview Lesson');
});
