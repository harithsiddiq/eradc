# Quickstart: Registration Email Transition

## 1. Mailtrap Test Setup

Populate local protected environment values from the Mailtrap inbox credentials using the variable contract in `contracts/environment.md`. Keep `QUEUE_CONNECTION=database`, then:

```bash
php artisan config:clear
php artisan migrate
php artisan queue:work --stop-when-empty
php artisan test --filter=RegistrationEmail
php artisan test --filter=EmailVerification
```

Register a test user and confirm exactly one message appears in Mailtrap with the correct signed link.

## 2. Hostinger Readiness

Complete every item in `contracts/operations.md` using the active Hostinger account panel or Hostinger support. Do not assume generic SMTP values apply to the account.

Verify:

- SMTP connectivity from the production server
- authorized sender identity
- SPF, DKIM, DMARC, and required mail-routing records
- account sending limits and shared-hosting cron/queue constraints
- production `APP_URL` and HTTPS

## 3. Production Deployment

Set protected production variables according to `contracts/environment.md`, then run:

```bash
php artisan migrate --force
php artisan config:clear
php artisan config:cache
php artisan queue:work --stop-when-empty
```

Configure Hostinger cron to invoke the chosen short-lived queue-processing command at the supported interval.

## 4. Acceptance and Rollback

Follow the production acceptance and rollback procedures in `contracts/operations.md`. Live delivery is accepted only after an external mailbox receives the correct verification email and the signed link activates the account.
