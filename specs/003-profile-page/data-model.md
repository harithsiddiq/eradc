# Data Model: Profile Page

## Entities

### Course
- `id`: bigint PK
- `slug`: varchar(255) unique
- `post_id`: bigint FK `posts.id` nullable
- `title`: json (translatable: ar, en)
- `description`: json (translatable)
- `thumbnail_path`: varchar(255) nullable
- `is_published`: boolean default false
- `order`: int default 0
- `created_at` / `updated_at`

### Lesson
- `id`: bigint PK
- `course_id`: bigint FK `courses.id`
- `slug`: varchar(255)
- `title`: json (translatable: ar, en)
- `description`: json (translatable)
- `video_url`: varchar(500) nullable
- `video_provider`: enum(hls,youtube,vimeo,direct) default hls
- `duration_seconds`: int nullable
- `order`: int default 0
- `is_published`: boolean default false
- `is_preview`: boolean default false
- `created_at` / `updated_at`
- *UNIQUE KEY*: (`course_id`, `slug`)

### Enrollment
- `id`: bigint PK
- `user_id`: bigint FK `users.id`
- `course_id`: bigint FK `courses.id`
- `enrolled_at`: timestamp
- `expires_at`: timestamp nullable
- `progress`: tinyint default 0
- `last_lesson_id`: bigint FK `lessons.id` nullable
- `status`: enum(active,expired,suspended) default active
- `created_at` / `updated_at`

### User (Modifications)
- Relationships added for `enrollments()` and `courses()`.
