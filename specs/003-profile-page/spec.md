# Feature Specification: Profile Page

**Feature Branch**: `[###-profile-page]`  
**Created**: 2026-06-15  
**Status**: Draft  
**Input**: User description: Phase 2 — Profile Page from SPEC.md

## User Scenarios & Testing *(mandatory)*

### User Story 1 - View Profile Details (Priority: P1)

As an authenticated user, I want to view my profile details (name, email) and update my password securely, so that I can manage my account access.

**Why this priority**: Core functionality for user self-service and security management.

**Independent Test**: Can be fully tested by navigating to the profile page and successfully changing the password using correct current password and valid new password.

**Acceptance Scenarios**:

1. **Given** an authenticated user is on the profile page, **When** they view the Account Info tab, **Then** they see their name and email (read-only).
2. **Given** an authenticated user is on the password change tab, **When** they submit a valid current password and a new confirmed password (min 8 chars, mixed case, numbers), **Then** their password is updated and a success message is shown.
3. **Given** an authenticated user is on the password change tab, **When** they submit an incorrect current password, **Then** an error is shown and the password is not changed.

---

### User Story 2 - View Enrolled Courses (Priority: P1)

As an authenticated user enrolled in courses, I want to see a list of my active enrolled courses with my progress, so that I can continue learning where I left off.

**Why this priority**: Essential for the primary value proposition of the platform (learning).

**Independent Test**: Can be tested independently by enrolling a user in a course via admin, logging in as the user, navigating to the profile's "My Courses" tab, and seeing the course card with correct progress and "Continue" button.

**Acceptance Scenarios**:

1. **Given** a user is enrolled in an active course, **When** they view the "My Courses" tab, **Then** they see a course card with title, thumbnail, progress bar, lesson count, and "Continue" button.
2. **Given** a user clicks "Continue" on a course card, **When** they have a `last_lesson_id`, **Then** they are navigated to that specific lesson page.
3. **Given** a user clicks "Continue" on a course card, **When** they have no `last_lesson_id` (new enrollment), **Then** they are navigated to the first lesson in the course.
4. **Given** a user is not enrolled in any courses, **When** they view the "My Courses" tab, **Then** they see an empty state message.

---

### User Story 3 - Manage Courses and Enrollments via Admin (Priority: P2)

As an admin, I want to create courses, add lessons to them, and manually enroll users into courses, so that users have access to learning materials.

**Why this priority**: Necessary for content management, but technically P2 because the data could be seeded manually for testing P1 features. Still essential for production.

**Independent Test**: Can be tested by logging into Filament admin, creating a Course, adding a Lesson, and assigning an Enrollment to a user.

**Acceptance Scenarios**:

1. **Given** an admin is in the Filament panel, **When** they create a Course, **Then** it supports translatable titles/descriptions and can be marked as published.
2. **Given** a Course exists, **When** an admin adds Lessons via relation manager, **Then** they can reorder them and set video details.
3. **Given** a User and a Course exist, **When** an admin creates an Enrollment, **Then** the user gains access to the course on their profile.

### Edge Cases

- What happens when a user's enrollment expires (`expires_at` in the past)? The course should no longer show under active courses, or should show as expired.
- How does system handle a course that gets unpublished after a user has enrolled? The lessons relationship filters by `is_published=true`, but the course might still show up.
- What happens if the `last_lesson_id` references a lesson that was deleted? The "Continue" button should probably fall back to the first available lesson.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide a secure password update mechanism enforcing minimum 8 characters, mixed case, and numbers.
- **FR-002**: System MUST display the user's active course enrollments including progress percentage and thumbnail.
- **FR-003**: System MUST route the user to their last viewed lesson when clicking "Continue", or the first lesson if never started.
- **FR-004**: System MUST provide an admin interface (Filament) to manage Courses, Lessons, and Enrollments.
- **FR-005**: System MUST support translatable (Arabic/English) titles and descriptions for Courses and Lessons.
- **FR-006**: System MUST persist the ordering of lessons within a course.
- **FR-007**: System MUST support manual admin enrollment of users to courses.

### Key Entities

- **User**: The platform user, who can have multiple enrollments.
- **Course**: A structured collection of lessons. Has translatable metadata.
- **Lesson**: A single learning unit within a course, containing video content.
- **Enrollment**: A pivot/join record linking a User to a Course, tracking their progress, access status, and last viewed lesson.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Users can successfully change their password and log in with the new credentials.
- **SC-002**: Users can view their enrolled courses and see accurate progress percentages.
- **SC-003**: Clicking "Continue" successfully navigates to the correct lesson URL based on the user's history.
- **SC-004**: Admins can successfully create a complete course structure and enroll a user within 5 minutes.

## Assumptions

- Admin panel (Filament) is already set up and accessible.
- Authentication views/mechanisms exist (aside from the profile page itself).
- "Manual admin enrollment" is sufficient for this phase (no e-commerce/payment integration yet).
- Courses are separate entities from regular Posts, though they can optionally link to a Post for marketing.
