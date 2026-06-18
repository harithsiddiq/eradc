# Phase 1: Data Model & State

**Feature**: Nav Profile Dropdown

## Entities

No new database entities or tables are required for Phase 1. 

The feature relies on the existing `User` model, specifically:
- `name`: Displayed in the dropdown and chip.
- `email`: Displayed in the dropdown.
- Initial character of `name`: Used for the avatar chip.

## Client-Side State (Alpine.js)

The dropdown component maintains local state:

- `open`: Boolean. Tracks whether the dropdown is currently visible. Toggled by clicking the avatar chip, and set to `false` when clicking outside the component.
