# Tasks: Production Email Transition

**Input**: Design documents from `/specs/001-production-email-transition/`  
**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/, quickstart.md

**Tests**: Included because the specification requires measurable registration-email, verification, failure, and configuration outcomes.

**Organization**: Tasks are grouped by user story so each story can be implemented and validated independently.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel because it targets different files and has no dependency on an incomplete task
- **[Story]**: Maps the task to its user story

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Establish the shared configuration and test structure used by all stories.

- [x] T001 Add non-secret SMTP, reply-to, timeout, and database queue placeholders to `.env.example`
- [x] T002 [P] Create the authentication feature-test directory and Pest scaffolding in `tests/Feature/Auth/RegistrationEmailTest.php` and `tests/Feature/Auth/EmailVerificationTest.php`
- [x] T003 [P] Add an operator-fillable Hostinger account and DNS readiness checklist to `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Complete shared mail and queue configuration before implementing any user story.

**CRITICAL**: No user story work begins until this phase is complete.

- [x] T004 Add environment-driven SMTP timeout and reply-to configuration to `config/mail.php`
- [x] T005 Apply configured reply-to values without exposing secrets in `app/Notifications/VerifyEmailNotification.php`
- [x] T006 [P] Verify the database queue tables exist and add the missing Laravel queue migration files under `database/migrations/` if required
- [x] T007 [P] Add a sanitized failed-job inspection and retry procedure to `specs/001-production-email-transition/contracts/operations.md`
- [ ] T008 Run `php artisan config:clear`, `php artisan migrate`, and focused configuration checks, then record any prerequisite corrections in `specs/001-production-email-transition/quickstart.md`

**Checkpoint**: Shared SMTP configuration and queued-notification infrastructure are ready.

---

## Phase 3: User Story 1 - Receive Registration Email (Priority: P1) MVP

**Goal**: A successful registration queues exactly one correctly addressed verification email, while failed or duplicate registrations queue none.

**Independent Test**: Register a unique user using a fake notification transport and confirm exactly one verification notification with a signed environment-specific link; submit invalid and duplicate registrations and confirm no additional notification.

### Tests for User Story 1

- [x] T009 [P] [US1] Add successful registration and exactly-one-notification tests to `tests/Feature/Auth/RegistrationEmailTest.php`
- [x] T010 [P] [US1] Add invalid-registration and duplicate-email suppression tests to `tests/Feature/Auth/RegistrationEmailTest.php`
- [x] T011 [P] [US1] Add notification subject, recipient, 60-minute signed-link, and active-`APP_URL` assertions to `tests/Feature/Auth/RegistrationEmailTest.php`

### Implementation for User Story 1

- [x] T012 [US1] Harden successful registration so user creation and `Registered` dispatch cannot produce partial or duplicate outcomes in `app/Http/Controllers/AuthController.php`
- [x] T013 [US1] Ensure the queued verification notification renders the approved subject, recipient-independent content, and signed environment-specific URL in `app/Notifications/VerifyEmailNotification.php` and `resources/views/emails/verify-email.blade.php`
- [x] T014 [US1] Run `php artisan test tests/Feature/Auth/RegistrationEmailTest.php` and resolve failures in `app/Http/Controllers/AuthController.php`, `app/Models/User.php`, and `app/Notifications/VerifyEmailNotification.php`

**Checkpoint**: User Story 1 is independently functional and testable as the MVP.

---

## Phase 4: User Story 2 - Prepare Production Email Configuration (Priority: P2)

**Goal**: Operators have a complete, secret-safe, account-specific inventory and release gate for Hostinger production email.

**Independent Test**: Complete the readiness checklist with values from the actual Hostinger account and confirm every required item is verified or explicitly marked not applicable without recording a secret.

### Tests for User Story 2

- [x] T015 [P] [US2] Add configuration-contract tests for required non-secret mail and queue keys in `tests/Feature/Auth/MailConfigurationTest.php`
- [x] T016 [P] [US2] Add repository secret-safety verification commands and expected results to `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`

### Implementation for User Story 2

- [ ] T017 [US2] Obtain and record the active Hostinger email product, mailbox, SMTP host, port, scheme, authentication rules, sender restrictions, credential owner, and support route in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`
- [ ] T018 [P] [US2] Obtain and record Hostinger hourly/daily sending limits, recipient/message/attachment limits, outbound-port restrictions, and anti-abuse rules in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`
- [ ] T019 [P] [US2] Obtain and record Hostinger cron support, minimum frequency, PHP CLI path/version, runtime limits, and persistent-worker availability in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`
- [ ] T020 [P] [US2] Record and verify domain ownership, MX where applicable, SPF, DKIM, DMARC, DNS status, and propagation expectations in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`
- [ ] T021 [US2] Review the completed inventory against the production readiness gate and sign off responsibility, credential rotation, and support escalation in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`
- [x] T022 [US2] Run `php artisan test tests/Feature/Auth/MailConfigurationTest.php` and the documented secret-safety checks, then resolve configuration-contract failures in `.env.example` and `config/mail.php`

**Checkpoint**: User Story 2 is complete when the actual Hostinger account passes every release gate without secrets entering tracked files.

---

## Phase 5: User Story 3 - Switch and Verify Live Delivery (Priority: P3)

**Goal**: Operators can safely enable Hostinger SMTP, verify external delivery and account activation, diagnose failures, and roll back.

**Independent Test**: Apply protected production values, process one queued registration notification, confirm delivery and activation through an external mailbox, then demonstrate the documented rollback.

### Tests for User Story 3

- [x] T023 [P] [US3] Add signed verification success, activation, invalid-signature, and expired-link tests to `tests/Feature/Auth/EmailVerificationTest.php`
- [x] T024 [P] [US3] Add resend-notification and throttle-limit tests to `tests/Feature/Auth/EmailVerificationTest.php`
- [x] T025 [P] [US3] Add queue-failure sanitization assertions to `tests/Feature/Auth/RegistrationEmailTest.php`

### Implementation for User Story 3

- [x] T026 [US3] Harden verification and resend route behavior, including already-verified handling and throttling, in `routes/web.php`
- [x] T027 [US3] Ensure successful signed verification sets both `email_verified_at` and `is_active` consistently in `routes/web.php` and `app/Models/User.php`
- [x] T028 [US3] Add sanitized queued-notification failure context without SMTP credentials or message secrets in `app/Notifications/VerifyEmailNotification.php`
- [ ] T029 [US3] Configure the chosen Hostinger short-lived queue-processing cron command and record the verified command and schedule in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`
- [ ] T030 [US3] Apply protected production SMTP values, rebuild the configuration cache, and record non-secret verification results in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`
- [ ] T031 [US3] Execute the external-mailbox acceptance procedure and record delivery time, sender identity, reply behavior, link host, activation result, and sanitized diagnostics in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`
- [ ] T032 [US3] Execute and time the rollback procedure, then record whether safe delivery state was restored within 15 minutes in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`
- [x] T033 [US3] Run `php artisan test tests/Feature/Auth/RegistrationEmailTest.php tests/Feature/Auth/EmailVerificationTest.php tests/Feature/Auth/MailConfigurationTest.php` and resolve all failures

**Checkpoint**: User Story 3 is complete when live delivery, activation, diagnostics, and rollback all pass.

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Final validation across all user stories.

- [ ] T034 [P] Review tracked files and logs for credential exposure and document the clean result in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`
- [x] T035 [P] Align operator commands and acceptance steps across `specs/001-production-email-transition/quickstart.md`, `specs/001-production-email-transition/contracts/environment.md`, and `specs/001-production-email-transition/contracts/operations.md`
- [ ] T036 Run `vendor/bin/pint --test` and the complete `php artisan test` suite, resolving feature-related regressions
- [ ] T037 Perform the full `specs/001-production-email-transition/quickstart.md` validation and mark final readiness in `specs/001-production-email-transition/checklists/hostinger-production-readiness.md`

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: Starts immediately.
- **Foundational (Phase 2)**: Depends on Setup and blocks all user stories.
- **User Story 1 (Phase 3)**: Depends on Foundational; delivers the MVP.
- **User Story 2 (Phase 4)**: Depends on Foundational and can proceed alongside User Story 1.
- **User Story 3 (Phase 5)**: Depends on User Story 1 for verified application behavior and User Story 2 for the completed production readiness gate.
- **Polish (Phase 6)**: Depends on all selected user stories.

### User Story Dependencies

```text
Setup -> Foundational -> US1 (MVP)
                      -> US2
US1 + US2 -> US3 -> Polish
```

- **US1**: No dependency on another story.
- **US2**: No dependency on another story; account-specific operator inputs are required.
- **US3**: Requires US1 and US2 because live switching must use a tested workflow and completed readiness inventory.

### Parallel Opportunities

- T002 and T003 can run in parallel after T001 starts.
- T006 and T007 can run in parallel with T004/T005.
- T009, T010, and T011 can be authored in parallel before US1 implementation.
- T015 and T016 can run in parallel.
- T018, T019, and T020 can run in parallel after T017 establishes the account.
- T023, T024, and T025 can be authored in parallel.
- T034 and T035 can run in parallel before final validation.

## Parallel Examples

### User Story 1

```text
Task T009: Add successful registration notification tests.
Task T010: Add failed and duplicate registration suppression tests.
Task T011: Add notification content and signed-link tests.
```

### User Story 2

```text
Task T018: Collect Hostinger sending limits and restrictions.
Task T019: Collect Hostinger cron and runtime capabilities.
Task T020: Verify domain authentication and DNS records.
```

### User Story 3

```text
Task T023: Add signed verification and activation tests.
Task T024: Add resend and throttle tests.
Task T025: Add queue-failure sanitization tests.
```

## Implementation Strategy

### MVP First

1. Complete Setup and Foundational phases.
2. Complete User Story 1.
3. Stop and validate registration produces exactly one correct queued verification email.

### Incremental Delivery

1. Deliver US1 as the tested registration-email MVP.
2. Complete US2 while keeping live delivery disabled.
3. Enable US3 only after the Hostinger readiness gate passes.
4. Finish cross-cutting validation and production sign-off.

## Notes

- Real Hostinger credentials belong only in protected production configuration, never in this repository.
- Tests should be written and observed failing before their corresponding implementation tasks.
- Operator tasks requiring the live Hostinger account cannot be completed from repository context alone.
- Commit after each task or cohesive task group.
