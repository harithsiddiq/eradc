# Research & Technical Decisions: Lesson Page with Video Player

## 1. Video Player Integration
**Decision**: Use a unified Blade component (`x-lesson.video-player`) that supports YouTube, Vimeo, and HLS (HTTP Live Streaming) or Direct formats based on the `video_provider` column of the `Lesson` model.
**Rationale**: Centralizing the video playback logic in one component keeps the `lesson.show` view clean. HLS.js is required for browsers that do not natively support HLS (like Chrome/Firefox), while Safari supports it natively.
**Alternatives considered**: Separate views for each provider. Rejected because it duplicates layout code and complicates the controller.

## 2. Progress Tracking Implementation
**Decision**: 
- **For HLS/Direct `<video>`**: Attach an event listener to the `timeupdate` event. Use lodash's debounce (or Alpine's `$watch` with debounce, or custom JS debounce) to periodically check `(currentTime / duration)`. If `>= 0.8`, fire a POST request to `/learn/{course}/{lesson}/progress`.
- **For YouTube/Vimeo iframes**: Provide a manual "Mark as Complete" button. Integrating the JS SDKs for both platforms adds significant overhead and requires user consent for cookies. A manual button is a resilient, standard fallback for embedded players.
**Rationale**: Reliable client-side tracking requires native DOM access. Iframes restrict this unless complex SDKs are used. A manual button ensures 100% reliability for external embeds.

## 3. SEO and Access Control
**Decision**: Use the `romanzip/laravel-seo` package (or manual meta tags if the package is overkill) to set `<meta name="robots" content="noindex, nofollow">` on the lesson page. 
**Rationale**: Lesson content must remain exclusive to enrolled users. Search engines crawling preview pages might expose them inappropriately or cause duplicate content issues if URLs are dynamic.

## 4. UI Layout & Responsiveness
**Decision**: Implement a sidebar layout using CSS Grid (`lg:grid-cols-[280px_1fr]`). On mobile screens, the sidebar becomes a collapsible drawer or stacks below the video player. Use Tailwind's RTL support (`rtl:` or `{{ app()->isLocale('ar') ? 'order-last' : 'order-first' }}`) to reverse the layout for Arabic.
**Rationale**: Standard, intuitive layout for e-learning platforms (e.g., Udemy, Coursera). Tailwind provides the necessary utility classes without custom CSS.
