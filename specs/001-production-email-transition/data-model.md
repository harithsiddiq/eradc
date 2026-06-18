# Data Model: Production Email Transition

## User

Existing persisted entity used by the registration and verification flow.

| Field | Type | Rules |
|---|---|---|
| `id` | identifier | Existing primary key |
| `name` | string | Required, maximum 255 characters |
| `email` | string | Required, valid format, unique, maximum 255 characters |
| `password` | secret hash | Required at registration, minimum 8 characters before hashing |
| `email_verified_at` | nullable timestamp | Set when signed verification succeeds |
| `is_active` | boolean | `false` at registration; `true` after verification |

**State transitions**:

```text
unregistered
  -> registered_unverified (user created, is_active=false, verification queued)
  -> verified_active (email_verified_at set, is_active=true)
```

## Email Environment Configuration

Deployment configuration, not application database data.

| Field | Sensitivity | Validation |
|---|---|---|
| environment name | public | One of local/testing/production |
| application URL | public | Absolute HTTPS URL in production |
| mailer | public | `smtp` for Mailtrap and Hostinger |
| scheme/encryption | public | Must match provider-selected port |
| SMTP host | public | Obtained from provider account |
| SMTP port | public | Numeric and allowed by hosting environment |
| SMTP username | secret-adjacent | Obtained from provider; protected |
| SMTP password | secret | Protected; never committed or logged |
| sender address/name | public | Sender address must be authorized |
| reply-to address/name | public | Optional, must be intentional |
| queue connection | public | Database-backed for this feature |

## Provider Configuration Inventory

Operational document completed before production enablement.

| Group | Required contents |
|---|---|
| Account | Hostinger account owner, plan/product, domain, mailbox, support route |
| SMTP | host, port, scheme, authentication, username, credential owner, timeout/certificate expectations |
| Sender | from address/name, reply-to, authorized sender restrictions |
| Limits | hourly/daily/message/recipient/attachment limits, anti-abuse and suspension rules |
| Hosting | outbound port allowance, cron frequency, PHP CLI path/version, queue execution limits |
| DNS | ownership, MX where applicable, SPF, DKIM, DMARC, record status and propagation |
| Operations | rotation, verification, monitoring, rollback, responsible operator |

## Delivery Event

Logical operational event; initially represented by queue/job state, sanitized logs, and provider records rather than a new table.

| Field | Rules |
|---|---|
| recipient | May appear in restricted operational logs; never public |
| event time | Required |
| environment | Required |
| outcome | queued, accepted, failed, or unknown |
| failure reason | Sanitized; no credentials or message secrets |
| queue job reference | Optional operational correlation |

## Relationships

- A `User` receives zero or more verification delivery attempts.
- An `Email Environment Configuration` selects one provider configuration and sender identity.
- A `Provider Configuration Inventory` validates the production environment before enablement.
