# Routes Contract: Lesson Page

## Public API Endpoints (Web routes, auth protected)

### 1. View Lesson Page
- **Method**: `GET`
- **Path**: `/learn/{course:slug}/{lesson:slug}`
- **Name**: `lesson.show`
- **Middleware**: `auth`, `verified`, `active`
- **Controller**: `LessonController@show`
- **Response**: HTML View (`lesson.show`)
- **Gates**:
  - If lesson `is_preview == true`, accessible to guests/unenrolled.
  - Else, requires active `Enrollment` for the user and course. Redirects to `/curses` with error message if unauthorized.

### 2. Update Lesson Progress
- **Method**: `POST`
- **Path**: `/learn/{course:slug}/{lesson:slug}/progress`
- **Name**: `lesson.progress`
- **Middleware**: `auth`, `verified`, `active`
- **Controller**: `LessonController@updateProgress`
- **Payload**: JSON
  ```json
  {
    "progress": 80
  }
  ```
- **Response**: JSON `{"ok": true}`
- **Validation**: `progress` must be an integer between 0 and 100.
- **Gates**: Requires active `Enrollment`. Returns 403 or 404 if not enrolled.
