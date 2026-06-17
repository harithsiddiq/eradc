# Phase 0: Research & Technical Decisions

**Feature**: Nav Profile Dropdown

## Research Areas

1. **Alpine.js Integration**: Alpine.js is already bundled via Filament/Vite. The dropdown can use `x-data="{ open: false }"` and `@click.outside="open = false"`.
2. **Tailwind CSS 4 RTL Support**: Tailwind 4 natively supports RTL using logical properties (e.g., `start-*`, `end-*`) or RTL pseudo-variants (`rtl:`). Since the app uses bilingual LTR/RTL, we will use logical classes like `text-start`, `ps-4`, etc. Or we can rely on `app()->isLocale('ar')` for specific absolute positioning if needed, though logical properties are preferred.
3. **Logout Form**: Laravel requires a POST request with `@csrf` for logging out. We will use a standard HTML `<form>` inside the dropdown for the logout action.
4. **Translations**: The translations are consolidated in `lang/en.json` and `lang/ar.json`. We will add new JSON keys for `nav.profile`, `nav.my_courses`, and `nav.logout`.

## Decisions

- **Decision**: Use Alpine.js for dropdown toggle logic.
- **Rationale**: It is already available in the tech stack, avoids heavy JS frameworks, and keeps logic co-located with HTML.

- **Decision**: Use `app()->isLocale('ar')` checks for positioning.
- **Rationale**: Ensures the dropdown aligns correctly depending on the user's selected language.
