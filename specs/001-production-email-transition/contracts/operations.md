# Contract: Hostinger Production Email Operations

## Information to Obtain from Hostinger

Before deployment, record and verify the following for the actual account:

- Email product/plan name, active status, account owner, domain, and production mailbox.
- SMTP hostname, SMTP port, required encryption/scheme, authentication requirement, username format, and password reset/rotation procedure.
- Whether the sender address must exactly match the authenticated mailbox and whether aliases are permitted.
- Outbound SMTP port restrictions from the shared-hosting server.
- Hourly and daily message limits, recipients-per-message limits, message/attachment size limits, and rate limits.
- Anti-spam, bounce, complaint, account suspension, and reinstatement rules.
- Required SPF, DKIM, DMARC, MX, ownership, or verification records and how Hostinger reports their status.
- DNS propagation guidance and the support channel for unresolved authentication/delivery failures.
- Cron support, minimum cron frequency, PHP CLI path/version, command time limits, and whether persistent queue workers are supported.

## Production Readiness Gate

Live delivery MUST remain disabled until:

1. The mailbox is active and credentials authenticate successfully.
2. SMTP host, port, and scheme work from the production server.
3. The production sender is authorized.
4. SPF and DKIM pass; DMARC is published with an intentional policy; required routing records are correct.
5. `APP_URL` is the public HTTPS production URL.
6. Queue storage is migrated and scheduled processing is active.
7. Provider limits are documented and suitable for expected registration volume.
8. No real secret is tracked in Git or printed in logs.

## Acceptance Procedure

1. Register a unique production test account addressed to an external mailbox.
2. Confirm exactly one queued verification notification.
3. Process the queued job through the production queue mechanism.
4. Confirm receipt within five minutes.
5. Verify sender name/address, reply behavior, content, HTTPS verification link, and 60-minute expiry behavior.
6. Follow the link and confirm the account becomes verified and active.
7. Inspect queue failures and application logs for sanitized output.
8. Repeat using a second external mailbox provider when practical.

## Rollback

If any gate or acceptance step fails:

1. Stop scheduled queue processing or switch the mailer to a non-live safe transport.
2. Preserve failed jobs and sanitized logs for diagnosis.
3. Clear/rebuild configuration after restoring the previous safe values.
4. Do not replay failed jobs until sender, links, recipients, and credentials are confirmed.
5. Re-run the complete acceptance procedure before enabling live delivery again.

## Failed Job Inspection And Retry

1. Inspect failures with `php artisan queue:failed`.
2. Review only sanitized exception context; do not paste job payloads into tickets or public channels.
3. Correct sender, SMTP, DNS, link-host, or queue configuration before retrying.
4. Retry a confirmed-safe job with `php artisan queue:retry <id>`.
5. Run `php artisan queue:work --stop-when-empty` and verify the expected recipient and message before retrying additional jobs.
6. Remove obsolete failed jobs only after confirming they must not be delivered.
