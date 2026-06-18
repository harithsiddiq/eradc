# Implementation Plan: Production Email Transition

**Branch**: `001-production-email-transition` | **Date**: 2026-06-14 | **Spec**: [spec.md](spec.md)
**Input**: Feature specification from `/specs/001-production-email-transition/spec.md`

## Summary

Complete and verify the existing queued registration-verification email flow, make its environment configuration explicit, add automated coverage for success/failure/duplicate behavior, and provide an operational checklist for moving from Mailtrap SMTP testing to an account-specific Hostinger SMTP configuration. Production credentials remain deployment secrets; Hostinger-specific endpoints and limits must be copied from the active account panel or confirmed with Hostinger support before release.

## Technical Context

**Language/Version**: PHP 8.2+  
**Primary Dependencies**: Laravel 12, Laravel Notifications/Mail/Queue, Symfony SMTP transport  
**Storage**: Existing MySQL database; database-backed queue; application logs for non-secret failure context  
**Testing**: Pest 4 with Laravel testing helpers, notification fakes, mail transport/config tests  
**Target Platform**: Hostinger shared hosting running the Laravel web application and scheduled queue processing  
**Project Type**: Server-rendered Laravel web application with Filament administration  
**Performance Goals**: Registration request completes without waiting for SMTP delivery; accepted queued messages are processed promptly enough to meet the five-minute delivery acceptance target  
**Constraints**: Shared-hosting process limits; no persistent worker assumption; SMTP credentials must never enter source control or logs; signed verification links depend on the correct production `APP_URL`; provider limits vary by account  
**Scale/Scope**: Registration verification email only; one production sender identity; Mailtrap test inbox; Hostinger production mailbox; no marketing or bulk email

## Constitution Check

The constitution file is an unratified placeholder and defines no enforceable gates. The plan applies these repository-compatible gates:

- PASS: Reuses Laravel's existing registration event, notification, and verification routes.
- PASS: Keeps provider credentials in environment configuration and out of tracked files.
- PASS: Adds focused feature tests for the user-facing registration and verification workflow.
- PASS: Avoids new persistence unless implementation proves delivery-event storage is required; operational failures use sanitized logging initially.
- PASS: Keeps production values account-specific instead of asserting unverified Hostinger settings.

**Post-design re-check**: PASS. The design remains scoped to existing Laravel boundaries, includes test and rollback contracts, and introduces no unjustified architecture.

## Project Structure

### Documentation (this feature)

```text
specs/001-production-email-transition/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
│   ├── environment.md
│   └── operations.md
└── tasks.md
```

### Source Code (repository root)

```text
app/
├── Http/Controllers/AuthController.php
├── Models/User.php
└── Notifications/VerifyEmailNotification.php

config/
└── mail.php

resources/views/
├── auth/verify-email.blade.php
└── emails/verify-email.blade.php

routes/
└── web.php

tests/
└── Feature/Auth/
    ├── RegistrationEmailTest.php
    └── EmailVerificationTest.php

.env.example
```

**Structure Decision**: Extend the existing single Laravel application. Registration remains in `AuthController`; verification delivery remains a queued Laravel notification; mail transport remains in `config/mail.php`; operational contracts live with the feature specification.

## Implementation Phases

### Phase 0 - Configuration and Delivery Decisions

1. Preserve the current `Registered` event and `MustVerifyEmail` notification mechanism.
2. Use SMTP for Mailtrap testing and Hostinger production; switch values through environment configuration only.
3. Treat the database queue as required because `VerifyEmailNotification` implements `ShouldQueue`.
4. Define an account-specific Hostinger readiness inventory and release gate.
5. Use sanitized application logging and provider dashboards for initial delivery diagnostics.

### Phase 1 - Application Hardening

1. Add explicit mail timeout and optional reply-to configuration where supported by the notification.
2. Update `.env.example` with documented non-secret mail and queue variables.
3. Ensure queue failures are observable without logging credentials or message secrets.
4. Keep signed verification URL generation tied to the environment's `APP_URL`.
5. Add feature tests for successful registration notification, failed/duplicate registration suppression, resend throttling, signed verification, and activation.

### Phase 2 - Deployment and Acceptance

1. Complete the Hostinger configuration inventory in the operations contract.
2. Verify DNS sender authentication and account sending restrictions.
3. Apply production secrets outside source control, clear/rebuild Laravel configuration cache, and run database migrations.
4. Configure the shared-hosting scheduler to process queued notifications without assuming a persistent daemon.
5. Perform external-mailbox acceptance testing and execute rollback if any release gate fails.

## Complexity Tracking

No constitution violations or additional architecture are required.
