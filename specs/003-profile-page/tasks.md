# Tasks: Profile Page

**Input**: Design documents from `/specs/003-profile-page/`
**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md, contracts/

**Tests**: Tests are included as explicitly requested in the feature specification (Pest tests).

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Project initialization and basic structure. Since this is an ongoing project, only minor setup is needed.

- [x] T001 Verify project environment and database connection.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [x] T002 Create migration for `courses` table in `database/migrations/xxxx_create_courses_table.php`
- [x] T003 Create migration for `lessons` table in `database/migrations/xxxx_create_lessons_table.php`
- [x] T004 Create migration for `enrollments` table in `database/migrations/xxxx_create_enrollments_table.php`
- [x] T005 [P] Create `EnrollmentStatus` enum in `app/Enums/EnrollmentStatus.php`
- [x] T006 [P] Create `Course` model in `app/Models/Course.php`
- [x] T007 [P] Create `Lesson` model in `app/Models/Lesson.php`
- [x] T008 [P] Create `Enrollment` model in `app/Models/Enrollment.php`
- [x] T009 Update `User` model relationships in `app/Models/User.php`
- [x] T010 Setup API routing for profile in `routes/web.php`
- [x] T011 Run database migrations.

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel

---

## Phase 3: User Story 1 - View Profile Details (Priority: P1) 🎯 MVP

**Goal**: As an authenticated user, I want to view my profile details and update my password securely.

**Independent Test**: Can be fully tested by navigating to the profile page and successfully changing the password.

### Tests for User Story 1 ⚠️

> **NOTE: Write these tests FIRST, ensure they FAIL before implementation**

- [ ] T012 [P] [US1] Integration test for viewing profile in `tests/Feature/Profile/ViewProfileTest.php`
- [ ] T013 [P] [US1] Integration test for updating password in `tests/Feature/Profile/UpdatePasswordTest.php`

### Implementation for User Story 1

- [ ] T014 [US1] Implement `ProfileController@show` (basic user info) and `updatePassword` in `app/Http/Controllers/ProfileController.php`
- [ ] T015 [US1] Create the profile view layout with tabs in `resources/views/profile/show.blade.php`
- [ ] T016 [US1] Add localization keys for profile (Arabic/English) in `lang/ar.json` and `lang/en.json`

**Checkpoint**: At this point, User Story 1 should be fully functional and testable independently

---

## Phase 4: User Story 2 - View Enrolled Courses (Priority: P1)

**Goal**: As an authenticated user enrolled in courses, I want to see a list of my active enrolled courses with my progress.

**Independent Test**: Can be tested independently by enrolling a user manually via DB/Admin, logging in, and checking the "My Courses" tab.

### Tests for User Story 2 ⚠️

- [ ] T017 [P] [US2] Integration test for viewing enrolled courses in `tests/Feature/Profile/ViewCoursesTest.php`

### Implementation for User Story 2

- [ ] T018 [US2] Update `ProfileController@show` to fetch active enrollments and course data in `app/Http/Controllers/ProfileController.php`
- [ ] T019 [US2] Implement the "My Courses" tab UI with course cards in `resources/views/profile/show.blade.php`

**Checkpoint**: At this point, User Stories 1 AND 2 should both work independently

---

## Phase 5: User Story 3 - Manage Courses and Enrollments via Admin (Priority: P2)

**Goal**: As an admin, I want to create courses, add lessons to them, and manually enroll users into courses.

**Independent Test**: Can be tested by logging into Filament admin, creating a Course, adding a Lesson, and assigning an Enrollment to a user.

### Implementation for User Story 3

- [ ] T020 [P] [US3] Create Filament `CourseResource` in `app/Filament/Resources/CourseResource.php`
- [ ] T021 [US3] Create Filament `LessonsRelationManager` in `app/Filament/Resources/CourseResource/RelationManagers/LessonsRelationManager.php`
- [ ] T022 [P] [US3] Create Filament `EnrollmentResource` in `app/Filament/Resources/EnrollmentResource.php`
- [ ] T023 [US3] Add localization keys for admin interface in `lang/ar.json` and `lang/en.json`

**Checkpoint**: All user stories should now be independently functional

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories

- [ ] T024 [P] Code cleanup and refactoring
- [ ] T025 Run Pest test suite and ensure all pass.

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion - BLOCKS all user stories
- **User Stories (Phase 3+)**: All depend on Foundational phase completion
  - User stories can then proceed sequentially in priority order (P1 → P2)
- **Polish (Final Phase)**: Depends on all desired user stories being complete

### User Story Dependencies

- **User Story 1 (P1)**: Can start after Foundational (Phase 2) - No dependencies on other stories
- **User Story 2 (P1)**: Can start after Foundational (Phase 2) - Integrates with US1's profile view.
- **User Story 3 (P2)**: Can start after Foundational (Phase 2) - Independent from the user-facing UI, manages the backend data for US2.

### Within Each User Story

- Tests MUST be written and FAIL before implementation
- Models before services
- Services before endpoints
- Core implementation before integration
- Story complete before moving to next priority

### Parallel Opportunities

- All Foundational Model/Enum creations marked [P] can run in parallel (within Phase 2)
- Tests for a user story marked [P] can run in parallel
- Filament resource creations marked [P] can run in parallel

---

## Implementation Strategy

### Incremental Delivery

1. Complete Setup + Foundational → Foundation ready
2. Add User Story 1 → Test independently (Profile view + Password change)
3. Add User Story 2 → Test independently (Courses tab)
4. Add User Story 3 → Test independently (Admin Panel)
5. Each story adds value without breaking previous stories
