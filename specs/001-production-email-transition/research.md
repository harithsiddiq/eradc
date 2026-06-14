# Research: Production Email Transition

## Existing Registration Integration

**Decision**: Retain the existing `Registered` event, `MustVerifyEmail` implementation, and custom queued `VerifyEmailNotification`.

**Rationale**: The registration controller already creates the user, dispatches `Registered`, logs the user in, and redirects to the verification notice. Laravel's registered-user listener invokes the model's verification notification, avoiding duplicate delivery logic.

**Alternatives considered**: Sending mail directly from the controller was rejected because it couples registration to SMTP delivery and risks duplicate emails. Creating a separate registration mailable was rejected because the existing verification notification already represents the required user journey.

## Queue Execution on Shared Hosting

**Decision**: Keep database-backed queued delivery and process it through Hostinger's cron/scheduler facilities using short-lived queue runs.

**Rationale**: `VerifyEmailNotification` implements `ShouldQueue`, and `.env.example` already selects the database queue. Shared hosting commonly cannot guarantee a permanently supervised worker, while scheduled short-lived processing is operationally compatible.

**Alternatives considered**: Synchronous delivery was rejected because SMTP latency or failure would delay registration. A permanent queue daemon remains acceptable only if the purchased Hostinger plan explicitly supports and supervises it.

## Environment Switching

**Decision**: Use the same SMTP environment-variable contract for Mailtrap and Hostinger; change values only in protected environment configuration.

**Rationale**: Laravel's existing mail configuration already reads the required SMTP variables. This minimizes code differences between test and production and keeps secrets out of source control.

**Alternatives considered**: Provider-specific API transports were rejected for this phase because the requested production target is Hostinger SMTP and no additional provider dependency is needed.

## Hostinger SMTP Values

**Decision**: Do not hardcode a universal Hostinger hostname, port, encryption mode, username format, or sending limit in implementation artifacts. Obtain them from the active mailbox's Hostinger control panel and confirm ambiguous values with Hostinger support.

**Rationale**: Values and restrictions can vary by email product, plan, region, and account state. The release must verify the actual account rather than rely on generic documentation.

**Alternatives considered**: Assuming commonly published Hostinger settings was rejected because a plausible but incorrect value would create a production outage.

## Sender Authentication

**Decision**: Require verification of the sender mailbox plus SPF, DKIM, DMARC, and applicable mail-routing records before enabling production delivery.

**Rationale**: SMTP authentication alone does not establish domain alignment or reliable inbox placement. DNS readiness is part of the release gate.

**Alternatives considered**: Enabling SMTP before DNS verification was rejected due to spoofing risk, spam placement, and avoidable rejection.

## Delivery Diagnostics

**Decision**: Use Laravel queue failure records/logs and the provider dashboard as the initial diagnostic sources, recording only recipient/event context and sanitized error information.

**Rationale**: This meets the operational need without introducing a new application delivery-events table during the first release.

**Alternatives considered**: A dedicated delivery-event persistence model was deferred because SMTP acceptance does not reliably represent final delivery and no webhook-capable provider is in scope.

## Verification Link Lifetime

**Decision**: Preserve the current 60-minute signed verification-link lifetime and ensure `APP_URL` is correct before queueing production mail.

**Rationale**: The current notification explicitly generates a 60-minute signed link. Correct host and scheme are essential because links are generated before delivery.

**Alternatives considered**: Changing the lifetime was rejected because the specification does not require a different user experience.
