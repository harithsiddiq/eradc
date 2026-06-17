# EradcHub Academy — System Overview

> Bilingual (Arabic/English) web application for a training and project management certification academy based in Riyadh, Saudi Arabia.

---

## Technology Stack

| Layer | Technology |
|-------|-----------|
| Language | PHP 8.2+ |
| Framework | Laravel 12 |
| Admin Panel | Filament 3.3 (Spatie translatable & settings plugins) |
| Frontend CSS | Tailwind CSS 4 (via Vite) |
| Build Tool | Vite 7 + laravel-vite-plugin |
| Database | MySQL |
| Session/Cache/Queue | Database-backed |
| Testing | Pest 4 |
| SEO | romanzip/laravel-seo |
| Translations | Spatie laravel-translatable (ar, en) |
| Settings | Spatie laravel-settings |
| Email | SMTP (Mailtrap dev, Hostinger prod) |
| Geo IP | ipapi.co API |

---

## Architecture

```
app/
├── Filament/           # Admin panel (resources, pages, widgets)
├── Http/Controllers/   # AuthController, HomeController, ExportController
├── Http/Middleware/     # TrackVisits, EnsureIsActive, SetLocale
├── Models/             # User, Post, Category, Media, PostMeta, Tag, Visit, Menu, MediaRelation
├── Notifications/      # VerifyEmailNotification (queued)
├── Providers/          # AppServiceProvider, Filament/AdminPanelProvider
├── Settings/           # FooterSettings (Spatie)
resources/views/
├── auth/               # Login, register, verify-email
├── components/         # hero, vision, trainers, services, projects, levels, blog, contact, logo-marquee
├── pages/              # Course listing & detail
├── main.blade.php      # Homepage layout
routes/
├── web.php             # All public & auth routes
├── console.php         # Scheduled tasks
```

---

## Data Models & Relationships

- **User** — `type` (admin/user), `level` (fresh/mid/consultant), `is_active`, signup geo-data (ip, country, region, city). Implements `MustVerifyEmail` and `FilamentUser`.
- **Post** — belongsTo User & Category, hasMany PostMeta & MediaRelation, belongsToMany Media. Translatable: title, content, excerpt, meta_title, meta_description. Fields: featured_image_path, show_on_landing.
- **Category** — self-referencing tree (parent_id), hasMany Post. Translatable: name, description, section_name, link. Fields: layout_style, show_in_menu, order, slug.
- **Media** — belongsToMany Post via MediaRelation pivot. Translatable: alt_text. Fields: file_name, file_path, file_type, file_size.
- **PostMeta** — belongsTo Post. Translatable: meta_key, meta_value.
- **Tag** — Translatable: name, description. Fields: slug, count.
- **Visit** — Tracks unique IP+date visits. Optional user_id. Fields: ip, date, path.
- **Menu** — belongsTo Category. Translatable: title. Fields: target_type (section/category/external), section_slug, external_url, order, is_active.
- **MediaRelation** — Pivot between Post & Media. Fields: relation_type, position, meta (JSON).

---

## Public Features

### Homepage (Dynamic Sections)
Categories with `layout_style` map to Blade components:
- `hero` — Hero banner
- `mission-vision` / `vision` — Vision & mission
- `trainers` — Trainer profiles
- `training-services` — Training service cards
- `engineering-projects` / `featured-projects` / `infra-projects` — Project showcases
- `logo-marquee` / `logo-marquee-partner` / `logo-marquee-standards` — Logo carousels
- `certificates` / `core-materials` / `levels` — Certification levels
- `contact-us` / `contact` — Contact form/section
- `posts` / `blog` — Blog preview

### Blog & Courses
- `/posts` — Paginated post listing
- `/post/{slug}` — Single post with author, category, meta
- `/curses` — Training courses listing
- `/curse/{slug}` — Single course detail

### Language Switching
- `/lang/{locale}` — Arabic (ar) / English (en) via session-based locale

---

## Authentication & Registration

| Route | Method | Description |
|-------|--------|-------------|
| `/register` | GET/POST | Registration with name, email, password, level. Captures geo-data via ipapi.co. Sets `is_active=false`, sends queued VerifyEmailNotification |
| `/login` | GET/POST | Email/password login with "remember me". Inactive users redirected to verification notice |
| `/email/verify` | GET | Verification notice page |
| `/email/verify/{id}/{hash}` | GET | Signed URL verification (60min expiry). Sets `is_active=true` on success |
| `/email/verification-notification` | POST | Resend verification email (throttled 6/min) |
| `/logout` | POST | Session invalidation + token regeneration |

Middleware: `EnsureIsActive` restricts unverified users to verification routes only.

---

## Filament Admin Panel (`/admin`)

### Dashboard
- SystemStatsWidget: categories, published posts, media, users, today's visits, total visits

### Resources
| Resource | Features |
|----------|----------|
| **PostResource** | Full CRUD, translatable, wizard form (Details → Content → SEO → Media → Extra Data), show_on_landing toggle |
| **CategoryResource** | Full CRUD, translatable, reorderable, layout_style auto-discovered from Blade components, self-referencing parent, PostsRelationManager |
| **MenuResource** | Full CRUD, dynamic menu builder (section/category/external targets), section slugs from config, reorderable |
| **UserResource** | View-only (no creation), infolist with signup geo-data, Arabic labels |
| **MediaResource** | Hidden from navigation |
| **TagResource** | Hidden from navigation |

### Settings
- **ManageFooter** — Bilingual tabs for: brand name, about text, address, copyright, social links (twitter, linkedin, instagram, tiktok, facebook), emails, phone, hours, policy links, contact section settings

---

## Visit Tracking
- `TrackVisits` middleware on all non-admin routes
- Records unique IP+date combinations in `visits` table
- Stores path and optional user_id

---

## Data Export
- `/admin/export-data` — Admin-only endpoint dumps categories, posts, post_meta, media as PHP seed files to `database/data/`

---

## Background Jobs & Queues
- **Queue driver**: Database (`QUEUE_CONNECTION=database`)
- **Queued jobs**: `VerifyEmailNotification` (ShouldQueue)
- **Queue worker**: `php artisan queue:listen --tries=1`
- **Dev workflow**: `composer dev` runs server + queue + pail + vite concurrently

---

## Security & Authorization
- Session-based auth (database session driver)
- Admin panel access: `User::canAccessPanel()` checks `type === 'admin'`
- Export route: additional `type !== 'admin'` check → 403
- Password hashing: Bcrypt with 12 rounds
- Signed email verification URLs (60-minute expiry)
- Throttled verification resend (6/min)

---

## Third-Party Integrations
1. **ipapi.co** — IP geolocation on registration (3s timeout, graceful failure)
2. **Mailtrap** — Dev SMTP testing
3. **Hostinger** — Planned production SMTP (see `specs/001-production-email-transition/`)
4. **Filament 3.3** — Admin panel framework
5. **Spatie** — Translatable + Settings packages
6. **romanzip/laravel-seo** — SEO meta management

---

## Specs & Documentation
- `specs/001-production-email-transition/` — Full spec for transitioning from Mailtrap to Hostinger production SMTP, including plan, research, data model, quickstart, contracts, tasks, and release checklists.
