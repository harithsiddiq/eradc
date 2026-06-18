# Feature Specification: Lesson Page with Video Player

**Feature Branch**: `004-lesson-page`  
**Created**: 2026-06-15  
**Status**: Draft  
**Input**: User description: "Phase 3 — Lesson Page with Video Player"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Watch Course Lessons (Priority: P1)

As an enrolled user, I want to access a lesson page with a video player so that I can consume the course content and view my progress alongside a list of all lessons in the course.

**Why this priority**: Core value proposition of the platform. Without a working video player and lesson list, the courses cannot be consumed.

**Independent Test**: Can be fully tested by enrolling a user in a course and navigating to the lesson page to ensure the video plays and the sidebar shows the correct lesson order.

**Acceptance Scenarios**:

1. **Given** I am an authenticated user enrolled in a course, **When** I navigate to a lesson page, **Then** I see the video player for that lesson and a sidebar listing all course lessons.
2. **Given** I am on a lesson page, **When** I look at the sidebar, **Then** I see my current progress percentage and clear indicators of which lessons are completed.
3. **Given** I am viewing a lesson, **When** the video reaches 80% completion, **Then** my progress is automatically updated and the lesson is marked as completed.

---

### User Story 2 - Preview Free Lessons (Priority: P2)

As a guest or unenrolled user, I want to watch lessons marked as previews so that I can evaluate the course quality before deciding to enroll.

**Why this priority**: Crucial for course marketing and conversion. It allows potential students to sample content.

**Independent Test**: Can be tested by accessing a preview lesson as a logged-out user and verifying the video plays, while non-preview lessons redirect to an enrollment prompt.

**Acceptance Scenarios**:

1. **Given** a lesson is marked as a preview, **When** an unenrolled user visits the lesson page, **Then** they can watch the video.
2. **Given** a lesson is not marked as a preview, **When** an unenrolled user visits the lesson page, **Then** they are redirected and shown a message that enrollment is required.

---

### User Story 3 - Seamless Video Playback Across Providers (Priority: P2)

As a user, I want videos to play reliably regardless of whether they are hosted on YouTube, Vimeo, or natively via HLS, so that my learning experience is uninterrupted.

**Why this priority**: The platform uses multiple video providers to serve content. The player must handle all gracefully.

**Independent Test**: Can be tested by creating three lessons (one YouTube, one Vimeo, one HLS) and verifying playback and progress tracking work for all three.

**Acceptance Scenarios**:

1. **Given** a lesson uses YouTube or Vimeo, **When** I load the page, **Then** the appropriate embedded player is displayed.
2. **Given** a lesson uses HLS, **When** I load the page, **Then** the native or HLS.js player is used to stream the video securely.

### Edge Cases

- What happens when a user's enrollment has expired? (They should be denied access to non-preview lessons).
- How does the system handle an unsupported video format or broken URL? (Should display an appropriate fallback message).
- What happens if the user reaches 80% of a video but their network connection drops before the progress is saved? (The progress won't be saved, and they may need to re-watch or manually mark as complete if a fallback button is provided).

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST restrict access to non-preview lessons to actively enrolled users only.
- **FR-002**: System MUST allow any user to view lessons explicitly marked as "preview".
- **FR-003**: System MUST provide a unified video player interface that supports YouTube, Vimeo, and HLS streams.
- **FR-004**: System MUST automatically track lesson progress and mark a lesson as complete when the user reaches 80% playback (for supported video types).
- **FR-005**: System MUST display a sidebar listing all published lessons in the course, ordered sequentially.
- **FR-006**: System MUST highlight the currently active lesson in the sidebar.
- **FR-007**: System MUST display "Previous" and "Next" navigation buttons to easily move between lessons.
- **FR-008**: System MUST update the user's `last_lesson_id` upon accessing a new lesson to track where they left off.
- **FR-009**: System MUST prevent search engines from indexing the lesson pages (SEO robots noindex) to protect private content.

### Key Entities

- **Lesson**: Represents a single instructional unit containing a video URL, provider type (YouTube, Vimeo, HLS, direct), title, description, and preview status.
- **Course**: The parent container for lessons, used to group them together and determine enrollment access.
- **Enrollment**: The association between a user and a course, tracking their overall progress and expiration status.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Enrolled users can successfully load and play a lesson video without errors.
- **SC-002**: The system automatically registers a lesson as complete when the user watches 80% of the video.
- **SC-003**: Unenrolled users are 100% blocked from accessing non-preview lessons.
- **SC-004**: Navigation between adjacent lessons works smoothly via the Previous/Next buttons.

## Assumptions

- Users have a stable internet connection capable of streaming video.
- Video URLs provided in the admin panel are valid and accessible.
- For YouTube/Vimeo, we assume standard embed parameters are sufficient for our needs.
- The platform is designed to be mobile-responsive, so the lesson sidebar will collapse or reposition on smaller screens.
