# Research: Profile Page

## Decisions

- **Decision:** Use standard Laravel blade templates for the profile view with Alpine.js for interactivity.
  - **Rationale:** Matches existing stack and Phase 1 implementation. Avoids introducing heavy frontend frameworks.
- **Decision:** Use Filament for admin interface.
  - **Rationale:** Filament is already the assumed standard in this project's spec for managing courses and enrollments.
- **Decision:** Use `Spatie\Translatable` for translatable model properties.
  - **Rationale:** Matches the stack context provided in `SPEC.md` (ar/en, RTL-first).
- **Decision:** Use MySQL for relational storage.
  - **Rationale:** Standard for Laravel; handles courses, lessons, and enrollments smoothly.
