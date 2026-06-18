# Data Model: Lesson Page with Video Player

*Note: The data models and database migrations for these entities were already created and executed during Phase 2 (User Profile).*

## Entities

### `Lesson` (Existing)
- **Table**: `lessons`
- **Fields**:
  - `id` (bigint PK)
  - `course_id` (bigint FK to courses)
  - `slug` (varchar)
  - `title` (json, translatable)
  - `description` (json, translatable)
  - `video_url` (varchar nullable)
  - `video_provider` (enum: hls, youtube, vimeo, direct)
  - `duration_seconds` (int nullable)
  - `order` (int)
  - `is_published` (boolean)
  - `is_preview` (boolean)
- **Relationships**: Belongs to `Course`.

### `Course` (Existing)
- **Table**: `courses`
- **Fields**:
  - `id` (bigint PK)
  - `title`, `description` (translatable json)
  - `is_published` (boolean)
- **Relationships**: Has Many `Lessons`, Has Many `Enrollments`.

### `Enrollment` (Existing)
- **Table**: `enrollments`
- **Fields**:
  - `id` (bigint PK)
  - `user_id` (bigint FK to users)
  - `course_id` (bigint FK to courses)
  - `progress` (tinyint, 0-100)
  - `last_lesson_id` (bigint FK to lessons nullable)
  - `status` (enum: active, expired, suspended)
- **Relationships**: Belongs to `User`, Belongs to `Course`, Belongs to `Lesson` (lastLesson).
