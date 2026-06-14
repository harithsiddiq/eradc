# Hostinger Production Email Readiness

Do not record passwords, tokens, or other secrets in this file.

## Account And SMTP

- [ ] Hostinger email product/plan and active status verified: `________________`
- [ ] Account owner and support route recorded: `________________`
- [ ] Production domain and mailbox recorded: `________________`
- [ ] SMTP hostname recorded: `________________`
- [ ] SMTP port and required scheme/encryption recorded: `________________`
- [ ] Authentication and username format verified: `________________`
- [ ] Authorized sender and alias restrictions verified: `________________`
- [ ] Credential owner and rotation procedure recorded without the credential: `________________`

## Limits And Hosting

- [ ] Hourly and daily sending limits recorded: `________________`
- [ ] Recipient, message-size, attachment, and rate limits recorded: `________________`
- [ ] Anti-abuse, bounce, complaint, suspension, and reinstatement rules recorded: `________________`
- [ ] Outbound SMTP port access verified from production: `________________`
- [ ] Cron support, minimum frequency, PHP CLI path/version, and runtime limits recorded: `________________`
- [ ] Persistent-worker availability explicitly confirmed or rejected: `________________`

## Domain Authentication

- [ ] Domain ownership verified: `________________`
- [ ] SPF record verified: `________________`
- [ ] DKIM record verified: `________________`
- [ ] DMARC record and intentional policy verified: `________________`
- [ ] Required MX or routing records verified: `________________`
- [ ] DNS propagation/status verified: `________________`

## Deployment And Acceptance

- [ ] Production `APP_URL` uses the public HTTPS URL: `________________`
- [ ] Queue migrations and scheduled queue processing verified: `________________`
- [ ] Verified short-lived queue cron command and schedule recorded: `________________`
- [ ] Protected SMTP values applied and configuration cache rebuilt: `________________`
- [ ] External delivery received within five minutes: `________________`
- [ ] Sender identity, reply behavior, content, and link host verified: `________________`
- [ ] Verification link activated the account: `________________`
- [ ] Sanitized failure diagnostics verified: `________________`
- [ ] Rollback restored a safe state within 15 minutes: `________________`

## Secret Safety

- [ ] `git grep -n -I -E 'MAIL_PASSWORD=.+|smtp://[^ ]+:[^ ]+@' -- ':!vendor' ':!node_modules'` returns no real credentials.
- [ ] Application and failed-job logs contain no SMTP passwords, SMTP URLs, or message secrets.
- [ ] Production credentials exist only in Hostinger's protected environment configuration.

## Sign-Off

- [ ] Production readiness gate approved by: `________________`
- [ ] Credential rotation owner: `________________`
- [ ] Support escalation owner: `________________`
- [ ] Approval date: `________________`
