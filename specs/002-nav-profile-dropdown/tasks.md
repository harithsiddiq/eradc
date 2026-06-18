# Tasks: Nav Profile Dropdown

**Input**: Design documents from `/specs/002-nav-profile-dropdown/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Project initialization and basic structure

*(No setup tasks needed as this is a frontend component inside an existing Laravel application)*

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [x] T001 Add new Arabic translation keys (`nav.profile`, `nav.my_courses`, `nav.logout`) to `lang/ar.json`
- [x] T002 [P] Add new English translation keys (`nav.profile`, `nav.my_courses`, `nav.logout`) to `lang/en.json`

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel

---

## Phase 3: User Story 1 - Authenticated User Navigation (Priority: P1) 🎯 MVP

**Goal**: Replace guest links with a profile dropdown for authenticated users

**Independent Test**: Log in as a user and verify the navigation bar shows the profile chip instead of login/register links, and the dropdown menu toggles successfully on click.

### Implementation for User Story 1

- [x] T003 [US1] Create `<x-nav.profile-dropdown />` component in `resources/views/components/nav/profile-dropdown.blade.php` with Alpine.js logic
- [x] T004 [US1] Create `<x-nav.auth-area />` component in `resources/views/components/nav/auth-area.blade.php` to conditionally render the dropdown or guest links
- [x] T005 [US1] Update `resources/views/components/include/header.blade.php` to use the `<x-nav.auth-area />` component instead of hardcoded guest links

**Checkpoint**: At this point, User Story 1 should be fully functional and testable independently

---

## Phase 4: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories

- [x] T006 Verify RTL alignment logic for the dropdown rendering correctly in Arabic locale

---

## Dependencies & Execution Order

### Phase Dependencies

- **Foundational (Phase 2)**: Can start immediately
- **User Stories (Phase 3+)**: All depend on Foundational phase completion
- **Polish (Final Phase)**: Depends on all desired user stories being complete

### User Story Dependencies

- **User Story 1 (P1)**: Can start after Foundational (Phase 2) - No dependencies on other stories

### Parallel Opportunities

- Translation updates (T001, T002) can run in parallel

---

## Parallel Example: User Story 1

```bash
# Update translations in parallel:
Task: "Add new Arabic translation keys... to lang/ar.json"
Task: "Add new English translation keys... to lang/en.json"
```

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 2: Foundational (CRITICAL)
2. Complete Phase 3: User Story 1
3. **STOP and VALIDATE**: Test User Story 1 independently by logging in.
