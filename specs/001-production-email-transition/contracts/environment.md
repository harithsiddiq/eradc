# Contract: Email Environment Configuration

## Required Application Variables

| Variable | Mailtrap Testing | Hostinger Production | Source/Owner |
|---|---|---|---|
| `APP_ENV` | Non-production value | `production` | Application owner |
| `APP_DEBUG` | Controlled locally | `false` | Application owner |
| `APP_URL` | Reachable test URL | Public HTTPS production URL | Application owner |
| `QUEUE_CONNECTION` | `database` | `database` | Application owner |
| `MAIL_MAILER` | `smtp` | `smtp` | Application owner |
| `MAIL_SCHEME` | Mailtrap-provided value | Hostinger-provided value | Email provider |
| `MAIL_HOST` | Mailtrap SMTP host | Hostinger SMTP host | Email provider |
| `MAIL_PORT` | Mailtrap SMTP port | Hostinger SMTP port | Email provider |
| `MAIL_USERNAME` | Mailtrap credential | Hostinger mailbox/SMTP username | Email provider |
| `MAIL_PASSWORD` | Mailtrap credential | Hostinger mailbox/SMTP password | Email provider |
| `MAIL_FROM_ADDRESS` | Approved test sender | Authorized production sender | Application/email owner |
| `MAIL_FROM_NAME` | Test display name | Approved production display name | Application owner |
| `MAIL_EHLO_DOMAIN` | Optional test domain | Production sending domain if required | Email/DNS owner |

## Optional Planned Variables

| Variable | Purpose |
|---|---|
| `MAIL_REPLY_TO_ADDRESS` | Explicit reply destination |
| `MAIL_REPLY_TO_NAME` | Reply destination display name |
| `MAIL_TIMEOUT` | Fail a stalled SMTP connection predictably |

## Security Rules

- Real usernames, passwords, tokens, and mailbox credentials MUST NOT be committed.
- Production secrets MUST be entered through the hosting environment or protected deployment mechanism.
- Logs and error pages MUST NOT render SMTP URLs, passwords, or complete transport configuration.
- Credential rotation MUST be followed by configuration-cache rebuild and a controlled delivery test.
- `.env.example` contains placeholders only.

## Configuration Activation

After any environment-variable change:

```bash
php artisan config:clear
php artisan config:cache
```

The active configuration must be inspected without printing secrets. A queued test notification must then be processed by the production queue mechanism.
