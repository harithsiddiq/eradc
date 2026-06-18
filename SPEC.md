# EradcHub — User Profile Feature
## Technical Specification `specs/002-user-profile-feature`

> **Stack context:** Laravel 12 · Blade · Tailwind CSS 4 · Alpine.js (implied by Filament/Livewire environment) · MySQL · Spatie laravel-translatable  
> **Locale:** Bilingual ar/en, RTL-first. All user-facing strings go through `__()` with both locale keys.  
> **Auth:** Session-based. `EnsureIsActive` middleware already in place.

---

## Overview

Three delivery phases:

| Phase | Scope |
|-------|-------|
| **P1** | Nav profile dropdown (replaces login/register buttons for authed users) |
| **P2** | Profile page — password change + enrolled courses list |
| **P3** | Lesson page with video player |

Data model additions land in **P2/P3**. P1 is pure UI with no schema changes.

---

## Phase 1 — Nav Profile Dropdown

### 1.1 Current State

The public nav (`resources/views/` layout, rendered in `main.blade.php` and the courses/posts layouts) shows:

```html
[تسجيل الدخول]  [إنشاء حساب]
```

for all visitors, with no authenticated-user variant.

### 1.2 Target Behaviour

| User state | Nav shows |
|------------|-----------|
| Guest | Login · Register buttons (unchanged) |
| Authenticated | Profile avatar/name chip → dropdown |

### 1.3 Dropdown Structure

```
┌─────────────────────────────┐
│  ● Harith Siddiq            │  ← name + avatar initial
│    harith@example.com       │  ← email (muted)
├─────────────────────────────┤
│  [ الملف الشخصي ]           │  → /profile
│  [ دوراتي        ]           │  → /profile#courses  (anchor scroll)
├─────────────────────────────┤
│  [ تسجيل الخروج  ]           │  POST /logout
└─────────────────────────────┘
```

Arabic labels / LTR equivalents:

| AR | EN | Route/Action |
|----|----|--------------|
| الملف الشخصي | My Profile | `GET /profile` |
| دوراتي | My Courses | `GET /profile#courses` |
| تسجيل الخروج | Logout | `POST /logout` |

### 1.4 Implementation

**Blade partial:** `resources/views/components/nav/profile-dropdown.blade.php`

```blade
@auth
<div x-data="{ open: false }" class="relative" @click.outside="open = false">

    {{-- Trigger chip --}}
    <button @click="open = !open"
            class="flex items-center gap-2 rounded-full px-3 py-1.5
                   bg-white/10 hover:bg-white/20 transition">
        <span class="w-8 h-8 rounded-full bg-[#1a3a5c] text-white
                     flex items-center justify-center text-sm font-bold select-none">
            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
        </span>
        <span class="hidden sm:block text-sm font-medium text-white">
            {{ auth()->user()->name }}
        </span>
        <svg class="w-4 h-4 text-white/70 transition-transform"
             :class="{ 'rotate-180': open }"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Dropdown panel --}}
    <div x-show="open" x-transition
         class="absolute {{ app()->isLocale('ar') ? 'left-0' : 'right-0' }} mt-2
                w-56 rounded-xl shadow-xl bg-white border border-gray-100 z-50
                divide-y divide-gray-100">

        {{-- Identity --}}
        <div class="px-4 py-3">
            <p class="text-sm font-semibold text-gray-800 truncate">
                {{ auth()->user()->name }}
            </p>
            <p class="text-xs text-gray-400 truncate">
                {{ auth()->user()->email }}
            </p>
        </div>

        {{-- Navigation --}}
        <div class="py-1">
            <a href="{{ route('profile.show') }}"
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                {{ __('nav.profile') }}
            </a>
            <a href="{{ route('profile.show') }}#courses"
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                {{ __('nav.my_courses') }}
            </a>
        </div>

        {{-- Logout --}}
        <div class="py-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-start px-4 py-2 text-sm text-red-600
                               hover:bg-red-50">
                    {{ __('nav.logout') }}
                </button>
            </form>
        </div>
    </div>
</div>
@else
    <a href="{{ route('login') }}" ...>{{ __('nav.login') }}</a>
    <a href="{{ route('register') }}" ...>{{ __('nav.register') }}</a>
@endauth
```

**Alpine.js** is already bundled via Filament/Vite — no additional install.

**Include in nav layout** (replace the existing guest links block):

```blade
{{-- resources/views/components/nav/auth-area.blade.php --}}
<x-nav.profile-dropdown />
```

**Lang keys** — add to `lang/ar.json` and `lang/en.json`:

```json
// ar.json additions
"nav.profile": "الملف الشخصي",
"nav.my_courses": "دوراتي",
"nav.logout": "تسجيل الخروج"

// en.json additions
"nav.profile": "My Profile",
"nav.my_courses": "My Courses",
"nav.logout": "Logout"
```

### 1.5 Route

No new route. Uses existing `logout` named route and the `profile.show` route added in P2.

---

## Phase 2 — Profile Page

### 2.1 New Data Models

#### 2.1.1 `Enrollment` (pivot + metadata)

Users enroll in courses (a `Post` where its `Category` has `layout_style = 'course'` or a new dedicated Course model — see §2.1.2).

```
enrollments
─────────────────────────────────────
id              bigint PK
user_id         bigint FK users.id
course_id       bigint FK courses.id
enrolled_at     timestamp
expires_at      timestamp nullable   ← for time-limited access
progress        tinyint default 0    ← 0-100 percent
last_lesson_id  bigint FK lessons.id nullable
status          enum(active,expired,suspended) default active
created_at / updated_at
```

#### 2.1.2 `Course` model

Courses currently live as `Post` rows under specific categories. Introduce a dedicated `courses` table to support structured lesson trees without polluting the blog/post model.

```
courses
─────────────────────────────────────
id              bigint PK
slug            varchar(255) unique
post_id         bigint FK posts.id nullable   ← link to marketing post
title           json (translatable: ar, en)
description     json (translatable)
thumbnail_path  varchar(255) nullable
is_published    boolean default false
order           int default 0
created_at / updated_at
```

```php
// App\Models\Course
use Spatie\Translatable\HasTranslations;

class Course extends Model
{
    use HasTranslations;
    public array $translatable = ['title', 'description'];

    public function lessons(): HasMany { return $this->hasMany(Lesson::class)->orderBy('order'); }
    public function enrollments(): HasMany { return $this->hasMany(Enrollment::class); }
    public function post(): BelongsTo { return $this->belongsTo(Post::class); }
}
```

#### 2.1.3 `Lesson` model

```
lessons
─────────────────────────────────────
id              bigint PK
course_id       bigint FK courses.id
slug            varchar(255)
title           json (translatable: ar, en)
description     json (translatable)
video_url       varchar(500) nullable       ← HLS/YouTube/Vimeo/direct
video_provider  enum(hls,youtube,vimeo,direct) default hls
duration_seconds int nullable
order           int default 0
is_published    boolean default false
is_preview      boolean default false       ← free preview without enrollment
created_at / updated_at

UNIQUE KEY (course_id, slug)
```

```php
// App\Models\Lesson
class Lesson extends Model
{
    use HasTranslations;
    public array $translatable = ['title', 'description'];

    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
}
```

#### 2.1.4 `Enrollment` model

```php
class Enrollment extends Model
{
    protected $casts = [
        'enrolled_at' => 'datetime',
        'expires_at'  => 'datetime',
        'status'      => EnrollmentStatus::class,  // PHP 8.1 backed enum
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function lastLesson(): BelongsTo { return $this->belongsTo(Lesson::class, 'last_lesson_id'); }
}
```

#### 2.1.5 `User` model additions

```php
public function enrollments(): HasMany
{
    return $this->hasMany(Enrollment::class);
}

public function courses(): BelongsToMany
{
    return $this->belongsToMany(Course::class, 'enrollments')
                ->withPivot(['enrolled_at', 'expires_at', 'progress', 'last_lesson_id', 'status'])
                ->withTimestamps();
}
```

### 2.2 Migrations

```
database/migrations/
  xxxx_xx_xx_create_courses_table.php
  xxxx_xx_xx_create_lessons_table.php
  xxxx_xx_xx_create_enrollments_table.php
```

Run order matters: courses → lessons → enrollments.

### 2.3 Routes

Add to `routes/web.php` inside the `auth` + `verified` middleware group:

```php
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
```

`active` = existing `EnsureIsActive` middleware alias.

### 2.4 `ProfileController`

`app/Http/Controllers/ProfileController.php`

```php
class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        $enrollments = $user->enrollments()
            ->with(['course.lessons' => fn($q) => $q->where('is_published', true)->orderBy('order')])
            ->where('status', EnrollmentStatus::Active)
            ->latest('enrolled_at')
            ->get();

        return view('profile.show', compact('user', 'enrollments'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', __('profile.password_updated'));
    }
}
```

### 2.5 Profile Page View

`resources/views/profile/show.blade.php`

Layout sections:

```
┌────────────────────────────────────────────────┐
│  PROFILE HEADER                                │
│  Avatar initial · Name · Email · Level badge   │
├────────────────────────────────────────────────┤
│  TABS (Alpine.js)                              │
│  [ معلوماتي ] [ تغيير كلمة المرور ] [ دوراتي ] │
│                                                │
│  ── Tab: Account Info (read-only) ─────────── │
│    Name, Email, Member since, Country/City     │
│                                                │
│  ── Tab: Change Password ───────────────────── │
│    current_password                            │
│    password (new)                              │
│    password_confirmation                       │
│    [ حفظ ]                                     │
│                                                │
│  ── Tab: My Courses (id="courses") ─────────── │
│    Course card grid (see §2.5.1)               │
└────────────────────────────────────────────────┘
```

#### 2.5.1 Course Card

Each enrolled course card shows:

- Thumbnail (course `thumbnail_path` or fallback SVG)
- Course title (locale-aware)
- Progress bar (0–100 %)
- Lesson count
- Last watched lesson label
- CTA button: **متابعة** / **Continue** → routes to `lesson.show` for `last_lesson_id`, or first lesson if null

```blade
<a href="{{ route('lesson.show', [$enrollment->course->slug, $nextLesson->slug]) }}"
   class="...">
    {{ __('profile.continue_course') }}
</a>
```

#### 2.5.2 Password Form Validation

Client-side: Alpine.js inline validation (match check before submit).  
Server-side: `current_password` rule + `Password` complexity rule.  
Flash: success/error via `session('success')` / `$errors`.

### 2.6 Filament Admin Additions

New resources in `app/Filament/Resources/`:

| Resource | Key features |
|----------|-------------|
| `CourseResource` | Full CRUD, translatable title/description, reorderable, `is_published` toggle, attach `post_id` for marketing link, `LessonsRelationManager` |
| `LessonResource` (relation manager) | Inline table inside CourseResource, drag-to-reorder, `video_url` + `video_provider` select, `is_preview` toggle |
| `EnrollmentResource` | Manual enrollment management: user select, course select, status, expiry |

`SystemStatsWidget` additions:

```php
Stat::make(__('admin.active_enrollments'), Enrollment::where('status', 'active')->count()),
Stat::make(__('admin.courses'), Course::where('is_published', true)->count()),
```

---

## Phase 3 — Lesson Page with Video Player

### 3.1 Routes

```php
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/learn/{course}/{lesson}', [LessonController::class, 'show'])
         ->name('lesson.show');
    Route::post('/learn/{course}/{lesson}/progress', [LessonController::class, 'updateProgress'])
         ->name('lesson.progress');
});
```

### 3.2 `LessonController`

```php
class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson): View|RedirectResponse
    {
        // Gate: must be enrolled OR lesson is a free preview
        $enrollment = auth()->user()
            ->enrollments()
            ->where('course_id', $course->id)
            ->where('status', EnrollmentStatus::Active)
            ->first();

        if (! $enrollment && ! $lesson->is_preview) {
            return redirect()->route('curses')
                ->with('error', __('lesson.enroll_required'));
        }

        // Update last_lesson_id on enrollment
        $enrollment?->update(['last_lesson_id' => $lesson->id]);

        $lessons = $course->lessons()->where('is_published', true)->orderBy('order')->get();

        $prevLesson = $lessons->where('order', '<', $lesson->order)->last();
        $nextLesson = $lessons->where('order', '>', $lesson->order)->first();

        return view('lesson.show', compact('course', 'lesson', 'lessons', 'prevLesson', 'nextLesson', 'enrollment'));
    }

    public function updateProgress(Course $course, Lesson $lesson, Request $request): JsonResponse
    {
        $enrollment = auth()->user()
            ->enrollments()
            ->where('course_id', $course->id)
            ->firstOrFail();

        $validated = $request->validate(['progress' => 'required|integer|min:0|max:100']);
        $enrollment->update(['progress' => $validated['progress']]);

        return response()->json(['ok' => true]);
    }
}
```

### 3.3 Lesson Page Layout

```
┌──────────────────────────────────────────────────────────────────┐
│ NAV (with profile dropdown)                                      │
├────────────────────────┬─────────────────────────────────────────┤
│  SIDEBAR               │  MAIN CONTENT AREA                      │
│  Course title          │                                         │
│  ─────────             │  ┌──────────────────────────────────┐   │
│  ● Lesson 1 ✓          │  │                                  │   │
│  ● Lesson 2 ✓          │  │         VIDEO PLAYER             │   │
│  ► Lesson 3 (current)  │  │                                  │   │
│  ○ Lesson 4            │  └──────────────────────────────────┘   │
│  ○ Lesson 5            │                                         │
│                        │  Lesson title                           │
│  Progress: 40%         │  Lesson description                     │
│  [████░░░░░░]          │                                         │
│                        │  ← Prev  |  Next →                      │
└────────────────────────┴─────────────────────────────────────────┘
```

**Responsive:** sidebar collapses to an accordion/drawer on mobile (`lg:grid-cols-[280px_1fr]`).

**RTL:** sidebar on right in ar locale, left in en. Use `{{ app()->isLocale('ar') ? 'order-last' : 'order-first' }}` or Tailwind `rtl:` variants.

### 3.4 Video Player Component

`resources/views/components/lesson/video-player.blade.php`

Supports three providers with a single component interface:

```blade
@props(['lesson'])

@php
    $provider = $lesson->video_provider;
    $url      = $lesson->video_url;
@endphp

@if($provider === 'youtube')
    {{-- Extract YouTube ID from URL --}}
    @php preg_match('/(?:v=|youtu\.be\/)([^&\?]+)/', $url, $m); $vid = $m[1] ?? ''; @endphp
    <div class="aspect-video w-full rounded-xl overflow-hidden bg-black">
        <iframe class="w-full h-full"
                src="https://www.youtube-nocookie.com/embed/{{ $vid }}?rel=0&modestbranding=1"
                allowfullscreen></iframe>
    </div>

@elseif($provider === 'vimeo')
    @php preg_match('/vimeo\.com\/(\d+)/', $url, $m); $vid = $m[1] ?? ''; @endphp
    <div class="aspect-video w-full rounded-xl overflow-hidden bg-black">
        <iframe class="w-full h-full"
                src="https://player.vimeo.com/video/{{ $vid }}?byline=0&portrait=0"
                allowfullscreen></iframe>
    </div>

@elseif($provider === 'hls')
    {{-- HLS via hls.js CDN for native-unsupported browsers --}}
    <div class="aspect-video w-full rounded-xl overflow-hidden bg-black">
        <video id="lesson-video" class="w-full h-full" controls playsinline>
            <source src="{{ $url }}" type="application/x-mpegURL">
        </video>
    </div>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script>
        (function() {
            const video = document.getElementById('lesson-video');
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource('{{ $url }}');
                hls.attachMedia(video);
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = '{{ $url }}';  // Safari native HLS
            }
        })();
    </script>
    @endpush

@else
    {{-- direct mp4/webm --}}
    <div class="aspect-video w-full rounded-xl overflow-hidden bg-black">
        <video class="w-full h-full" controls playsinline>
            <source src="{{ $url }}">
            {{ __('lesson.video_unsupported') }}
        </video>
    </div>
@endif
```

### 3.5 Progress Tracking (Client-Side)

Fires a debounced `POST /learn/{course}/{lesson}/progress` when the user reaches 80 % playback, marking the lesson as complete. For non-HLS videos (iframes), relies on the YouTube/Vimeo JS SDK postMessage API if available, otherwise uses a manual "Mark as complete" button.

```js
// Pushed via @push('scripts') on HLS/direct videos
video.addEventListener('timeupdate', debounce(function() {
    const pct = Math.round((video.currentTime / video.duration) * 100);
    if (pct >= 80) {
        fetch('{{ route("lesson.progress", [$course->slug, $lesson->slug]) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ progress: pct })
        });
    }
}, 5000));
```

---

## Cross-Cutting Concerns

### Security

| Concern | Implementation |
|---------|---------------|
| Lesson access gate | Controller checks `Enrollment` before rendering lesson |
| Preview bypass | `is_preview = true` on `Lesson` allows guest/unenrolled viewing |
| CSRF | All POST forms use `@csrf`; AJAX uses `X-CSRF-TOKEN` header |
| Signed URLs | Not required for streaming — server-side gate is sufficient; add signed S3/CDN URLs if video storage is sensitive |

### Middleware

`EnsureIsActive` already redirects inactive users. The profile and lesson routes sit inside the same group — no extra middleware needed.

### Localization

All new string keys follow the existing `__('key')` pattern. Suggested key namespaces:

```
nav.*       — navbar strings (P1)
profile.*   — profile page strings (P2)
lesson.*    — lesson page strings (P3)
admin.*     — filament admin strings
```

Add keys to both `lang/ar.json` and `lang/en.json`.

### SEO

Lesson and profile pages should set canonical + noindex via `romanzip/laravel-seo` to avoid indexing private content:

```php
// In LessonController::show()
SEO::setTitle($lesson->title);
SEO::setDescription($lesson->description);
// Prevent search engine indexing of authenticated content
SEO::metatags()->addMeta('robots', 'noindex, nofollow');
```

---

## File Map Summary

```
app/
├── Http/
│   └── Controllers/
│       ├── ProfileController.php          [P2]
│       └── LessonController.php           [P3]
├── Models/
│   ├── Course.php                         [P2]
│   ├── Lesson.php                         [P3]
│   └── Enrollment.php                     [P2]
├── Enums/
│   └── EnrollmentStatus.php               [P2]
├── Filament/Resources/
│   ├── CourseResource.php                 [P2]
│   ├── CourseResource/
│   │   └── RelationManagers/
│   │       └── LessonsRelationManager.php [P3]
│   └── EnrollmentResource.php             [P2]

database/migrations/
├── xxxx_create_courses_table.php
├── xxxx_create_lessons_table.php
└── xxxx_create_enrollments_table.php

resources/views/
├── components/
│   ├── nav/
│   │   ├── profile-dropdown.blade.php     [P1]
│   │   └── auth-area.blade.php            [P1]
│   └── lesson/
│       └── video-player.blade.php         [P3]
├── profile/
│   └── show.blade.php                     [P2]
└── lesson/
    └── show.blade.php                     [P3]

lang/
├── ar.json   (additions: nav.*, profile.*, lesson.*)
└── en.json   (additions: nav.*, profile.*, lesson.*)

routes/web.php   (additions: profile.show, profile.password, lesson.show, lesson.progress)
```

---

## Implementation Order

```
P1  →  nav/profile-dropdown + auth-area components
        lang keys (nav.*)
        Manual smoke-test: guest vs. authed nav

P2  →  Migrations: courses, lessons, enrollments
        Models + Enum
        User model relationship additions
        ProfileController (show + updatePassword)
        profile/show.blade.php (3 tabs)
        Filament: CourseResource, EnrollmentResource
        lang keys (profile.*)
        Pest tests: password update, profile view, enrollment gate

P3  →  LessonController (show + updateProgress)
        lesson/show.blade.php (sidebar + player area)
        components/lesson/video-player.blade.php
        Filament: LessonsRelationManager inside CourseResource
        lang keys (lesson.*)
        Pest tests: lesson access gate, progress update, preview bypass
```

---

## Open Questions / Decisions Required

| # | Question | Default assumed |
|---|----------|----------------|
| 1 | Are courses enrolled manually by admin or via a payment flow? | Manual admin enrollment (Filament) for now |
| 2 | Video hosting: self-hosted HLS on VPS, YouTube unlisted, or Vimeo? | Component supports all three; provider set per-lesson |
| 3 | Should lesson progress reset on re-enroll? | No — progress is per `enrollment` row, a new enrollment row resets it |
| 4 | Is a "certificate" generated on 100 % course completion? | Out of scope for this spec; hook point: `Enrollment::updated` observer |
| 5 | Course access expiry enforcement? | `expires_at` column in place; gate check added in `LessonController` |
