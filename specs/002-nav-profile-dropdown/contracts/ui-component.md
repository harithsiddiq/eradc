# Component Contract: Profile Dropdown

**Type**: Blade Component (`<x-nav.profile-dropdown />`)

## Props
None. The component is self-contained and pulls user data directly from the `auth()->user()` helper.

## Dependencies
- Requires an authenticated session (`auth()->check()` must be true).
- Requires Alpine.js in the global scope for interactivity.
