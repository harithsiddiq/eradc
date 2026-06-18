# Tasks: Lesson Page with Video Player

**Input**: Design documents from `/specs/004-lesson-page/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/, quickstart.md

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Phase 1: Setup

**Purpose**: Verify the environment and existing dependencies.

- [x] T001 Verify existing models (`Course`, `Lesson`, `Enrollment`) and migrations are present and active.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be fully verified end-to-end (specifically data entry).

- [x] T002 [P] Create `LessonsRelationManager` in `app/Filament/Resources/CourseResource/RelationManagers/LessonsRelationManager.php` to allow admin creation of lessons.

**Checkpoint**: Foundation ready - user story implementation can now begin.

---

## Phase 3: User Story 1 - Watch Course Lessons (Priority: P1) 🎯 MVP

**Goal**: As an enrolled user, I want to access a lesson page with a video player so that I can consume the course content.

**Independent Test**: Can be fully tested by enrolling a user in a course and navigating to the lesson page to ensure the view loads the course's lessons in the sidebar.

### Implementation for User Story 1

- [x] T003 [P] [US1] Create integration test for lesson view access in `tests/Feature/Lesson/ViewLessonTest.php`
- [x] T004 [US1] Implement `LessonController@show` in `app/Http/Controllers/LessonController.php` (gate checks, retrieve lesson and sibling lessons)
- [x] T005 [US1] Create the base UI layout in `resources/views/lesson/show.blade.php` with sidebar lesson list
- [x] T006 [P] [US1] Add localization keys for the lesson UI in `lang/en.json` and `lang/ar.json`

**Checkpoint**: At this point, User Story 1 should be fully functional and the user can navigate the lesson sidebar.

---

## Phase 4: User Story 2 - Preview Free Lessons (Priority: P2)

**Goal**: As a guest or unenrolled user, I want to watch lessons marked as previews.

**Independent Test**: Can be tested by accessing a preview lesson as a logged-out user and verifying access is granted, while non-preview lessons redirect to an enrollment prompt.

### Implementation for User Story 2

- [x] T007 [P] [US2] Create integration test for preview bypass in `tests/Feature/Lesson/PreviewLessonTest.php`
- [x] T008 [US2] Update `LessonController@show` in `app/Http/Controllers/LessonController.php` to allow `is_preview` bypass
- [x] T009 [US2] Add visual indicator for preview lessons in the sidebar of `resources/views/lesson/show.blade.php`

**Checkpoint**: At this point, User Stories 1 AND 2 should both work independently. Guests can access free previews.

---

## Phase 5: User Story 3 - Seamless Video Playback Across Providers (Priority: P2)

**Goal**: Videos play reliably regardless of whether they are hosted on YouTube, Vimeo, or natively via HLS, and progress is tracked.

**Independent Test**: Tested by verifying that the correct iframe or native video tag renders, and the `/progress` endpoint updates the enrollment state.

### Implementation for User Story 3

- [x] T010 [P] [US3] Create integration test for progress API in `tests/Feature/Lesson/ProgressTrackingTest.php`
- [x] T011 [P] [US3] Implement unified video player component in `resources/views/components/lesson/video-player.blade.php`
- [x] T012 [US3] Implement `LessonController@updateProgress` in `app/Http/Controllers/LessonController.php`
- [x] T013 [US3] Add client-side progress tracking JS and fallback buttons in `resources/views/components/lesson/video-player.blade.php`
- [x] T014 [US3] Embed the video component into the main `resources/views/lesson/show.blade.php` view.

**Checkpoint**: All user stories should now be independently functional. Video progress successfully saves.

---

## Phase N: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories

- [x] T015 [P] Implement SEO meta tags (noindex) for private lessons in `resources/views/lesson/show.blade.php`
- [x] T016 [M] Test mobile responsiveness of the sidebar and video player. devices in `resources/views/lesson/show.blade.php`

---

## Dependencies & Execution Order

### Phase Dependencies
- **Setup (Phase 1)**: Can start immediately.
- **Foundational (Phase 2)**: Can start immediately.
- **User Stories (Phase 3+)**: Depend on Foundational.
- **Polish (Final Phase)**: Depends on all desired user stories being complete.

### Parallel Opportunities
- Test creation (`ViewLessonTest.php`, `ProgressTrackingTest.php`) can happen in parallel with component scaffolding.
- The `video-player.blade.php` component can be developed independently from the main `LessonController`.
- Localization strings can be added anytime.
