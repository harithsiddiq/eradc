# Quickstart: Lesson Page Feature

## Overview
This feature implements the core e-learning consumption view. It allows authenticated users to watch course videos (YouTube, Vimeo, or HLS) and automatically tracks their progress.

## Setup Instructions

1. **Database & Models**
   The models and migrations (`Course`, `Lesson`, `Enrollment`) are already implemented. No further database schema changes are required.

2. **Routes**
   Add the following routes to `routes/web.php` inside the `auth` middleware group:
   ```php
   Route::get('/learn/{course:slug}/{lesson:slug}', [LessonController::class, 'show'])->name('lesson.show');
   Route::post('/learn/{course:slug}/{lesson:slug}/progress', [LessonController::class, 'updateProgress'])->name('lesson.progress');
   ```

3. **Controllers**
   Create `app/Http/Controllers/LessonController.php`.
   - Implement `show` method: check for enrollment, determine `prevLesson` and `nextLesson`, and return the view.
   - Implement `updateProgress` method: validate the payload and update the `progress` on the `Enrollment` model.

4. **Views**
   - Create the layout: `resources/views/lesson/show.blade.php`. Include a sidebar with the list of lessons and the main video area.
   - Create the video player component: `resources/views/components/lesson/video-player.blade.php`. Implement logic to switch between YouTube iframe, Vimeo iframe, and HLS `<video>` tag depending on `video_provider`.

5. **Client-Side Tracking**
   - For HLS/Direct `<video>`, include a script to track `timeupdate` and send an AJAX POST request when playback reaches 80%.
   - For embedded iframes (YouTube/Vimeo), provide a manual "Mark as Complete" button that triggers the same POST request.

## Testing
- **Enrollment Gate**: Try accessing a non-preview lesson without being enrolled. It should redirect.
- **Video Playback**: Enroll a user and test that HLS videos play (requires `hls.js` CDN) and YouTube/Vimeo embeds load correctly.
- **Progress Tracking**: Fast-forward a native video past 80% and verify that the `progress` endpoint is hit via the network tab.
