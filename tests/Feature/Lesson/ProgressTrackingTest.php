<?php

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

test('enrolled user can update progress', function () {
    $user = User::factory()->create();

    $course = Course::create([
        'slug' => 'test-course',
        'title' => ['en' => 'Test Course', 'ar' => 'Test Course AR'],
        'description' => ['en' => 'Description', 'ar' => 'Description AR'],
        'is_published' => true,
    ]);

    $lesson = Lesson::create([
        'course_id' => $course->id,
        'slug' => 'test-lesson',
        'title' => ['en' => 'Test Lesson', 'ar' => 'Test Lesson AR'],
        'description' => ['en' => 'Lesson desc', 'ar' => 'Lesson desc AR'],
        'video_url' => 'https://example.com/video.mp4',
        'video_provider' => 'direct',
        'duration_seconds' => 120,
        'order' => 1,
        'is_published' => true,
        'is_preview' => false,
    ]);

    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'progress' => 0,
        'status' => 'active',
    ]);

    $response = $this->actingAs($user)->postJson(route('lesson.progress', ['course' => $course->slug, 'lesson' => $lesson->slug]), [
        'progress' => 85,
    ]);

    $response->assertStatus(200);
    $response->assertJson(['ok' => true]);

    $this->assertDatabaseHas('enrollments', [
        'id' => $enrollment->id,
        'progress' => 85,
        'last_lesson_id' => $lesson->id,
    ]);
});

test('unenrolled user cannot update progress', function () {
    $user = User::factory()->create();

    $course = Course::create([
        'slug' => 'test-course',
        'title' => ['en' => 'Test Course', 'ar' => 'Test Course AR'],
        'description' => ['en' => 'Description', 'ar' => 'Description AR'],
        'is_published' => true,
    ]);

    $lesson = Lesson::create([
        'course_id' => $course->id,
        'slug' => 'test-lesson',
        'title' => ['en' => 'Test Lesson', 'ar' => 'Test Lesson AR'],
        'description' => ['en' => 'Lesson desc', 'ar' => 'Lesson desc AR'],
        'video_url' => 'https://example.com/video.mp4',
        'video_provider' => 'direct',
        'duration_seconds' => 120,
        'order' => 1,
        'is_published' => true,
        'is_preview' => true,
    ]);

    $response = $this->actingAs($user)->postJson(route('lesson.progress', ['course' => $course->slug, 'lesson' => $lesson->slug]), [
        'progress' => 85,
    ]);

    $response->assertStatus(403);
});
