# Feature Specification: Nav Profile Dropdown

**Feature Branch**: `002-nav-profile-dropdown`  
**Created**: 2026-06-14  
**Status**: Draft  
**Input**: User description: "create specification for Nav Profile Dropdown phase read it from /Users/harithsiddiq/Sites/eradc/SPEC.md"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Authenticated User Navigation (Priority: P1)

As an authenticated user, I want to see my profile avatar and name in the navigation bar instead of login/register buttons, and click it to open a dropdown menu so that I can easily access my profile, my enrolled courses, or log out.

**Why this priority**: This is the core functionality of the feature that improves the authenticated user experience across the entire site.

**Independent Test**: Can be fully tested by logging in as a user and verifying the navigation bar displays the user's name/initial, and the dropdown contains the correct links that route to the correct destinations.

**Acceptance Scenarios**:

1. **Given** I am a guest visitor, **When** I view the navigation bar, **Then** I should see the "Login" and "Register" buttons.
2. **Given** I am an authenticated user, **When** I view the navigation bar, **Then** I should see a chip with my name and avatar initial.
3. **Given** I am an authenticated user, **When** I click the profile chip in the navigation bar, **Then** a dropdown menu should open displaying my full name, email, and links to "My Profile", "My Courses", and "Logout".
4. **Given** I have the profile dropdown open, **When** I click outside the dropdown, **Then** the dropdown should close.

### Edge Cases

- What happens when a user's name is very long? (It should be truncated to fit the UI gracefully).
- What happens when a user's email is very long? (It should be truncated in the dropdown).

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST display "Login" and "Register" links to guest users in the navigation bar.
- **FR-002**: System MUST display a profile chip with the user's name and initial to authenticated users in the navigation bar.
- **FR-003**: Users MUST be able to click the profile chip to toggle a dropdown menu.
- **FR-004**: System MUST display the user's name, email, and links to "My Profile", "My Courses", and "Logout" inside the dropdown menu.
- **FR-005**: System MUST log the user out when the "Logout" link is clicked via a POST request.
- **FR-006**: System MUST support bilingual text (Arabic and English) for all navigation and dropdown labels.

### Key Entities

- **User**: The authenticated visitor interacting with the navigation menu.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: 100% of authenticated users see the profile dropdown instead of guest links.
- **SC-002**: Users can successfully log out via the dropdown menu.
- **SC-003**: The dropdown UI is responsive and renders correctly on both desktop and mobile viewports.

## Assumptions

- Assumes the authentication system (session-based) is already in place.
- Assumes the Arabic and English locale system is already configured.
- Assumes Alpine.js and Tailwind CSS are available for the UI implementation as per the project context.
