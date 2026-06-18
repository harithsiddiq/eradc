# Feature Specification: Production Email Transition

**Feature Branch**: `001-production-email-transition`  
**Created**: 2026-06-14  
**Status**: Draft  
**Input**: User description: "Integrate email notifications into user registration, test with Mailtrap, and prepare a comprehensive Hostinger shared-hosting configuration inventory for the live production transition."

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Receive Registration Email (Priority: P1)

As a newly registered user, I receive the expected registration email at the address I provided so that I can complete or confirm the registration journey.

**Why this priority**: Registration email delivery is the primary user-facing value of the feature.

**Independent Test**: Register a new account in the testing environment and confirm that exactly one correctly addressed registration email is captured with the expected subject, content, and action link.

**Acceptance Scenarios**:

1. **Given** a visitor provides a valid unused email address, **When** registration succeeds, **Then** one registration email is prepared for that address.
2. **Given** a registration email contains an action link, **When** the user follows the link, **Then** the link points to the environment where the registration occurred.
3. **Given** registration does not succeed, **When** the attempted registration ends, **Then** no registration-success email is sent.

---

### User Story 2 - Prepare Production Email Configuration (Priority: P2)

As a deployment operator, I can gather and verify every required production email setting and sender-domain prerequisite before switching away from the testing environment.

**Why this priority**: A complete, verified configuration inventory prevents failed delivery, insecure secret handling, and avoidable production downtime.

**Independent Test**: Complete the production-readiness checklist using values obtained from the hosting and email providers, with every required item either verified or explicitly marked as not applicable.

**Acceptance Scenarios**:

1. **Given** production email service access is available, **When** the operator completes the inventory, **Then** it records the mail transport type, SMTP host, SMTP port, encryption mode, authentication requirement, username, password or credential, sender address, sender name, and reply-to details.
2. **Given** a custom sender domain is used, **When** the operator completes domain readiness checks, **Then** ownership, SPF, DKIM, DMARC, and required mail-routing records are documented and verified.
3. **Given** production credentials are collected, **When** they are stored for deployment, **Then** secret values are excluded from source control and user-visible documentation.
4. **Given** Hostinger shared hosting imposes sending limits or restrictions, **When** readiness is reviewed, **Then** hourly/daily limits, attachment limits, permitted sender identities, authentication restrictions, and account suspension conditions are documented.

---

### User Story 3 - Switch and Verify Live Delivery (Priority: P3)

As a deployment operator, I can switch the application from testing delivery to live delivery and verify the result without changing the registration experience.

**Why this priority**: The transition must be controlled, observable, and reversible if live delivery fails.

**Independent Test**: Apply the production configuration in a controlled release, register a production test account, confirm delivery to an external mailbox, and restore the testing configuration if verification fails.

**Acceptance Scenarios**:

1. **Given** the production checklist is complete, **When** production email delivery is enabled, **Then** registration emails are sent through the approved live email account.
2. **Given** live delivery is enabled, **When** a production test user registers, **Then** the email arrives at an external mailbox and displays the approved sender identity.
3. **Given** live delivery verification fails, **When** the operator initiates rollback, **Then** outgoing registration emails can be returned to a non-live or disabled state without exposing credentials.
4. **Given** a delivery attempt fails, **When** the failure is recorded, **Then** operators can identify the affected recipient, time, and failure reason without exposing secret configuration values.

### Edge Cases

- The SMTP hostname resolves but outbound SMTP connections are blocked by the hosting environment.
- The selected port and encryption mode do not match.
- Authentication succeeds but the configured sender address is not authorized.
- DNS authentication records are missing, duplicated, malformed, or still propagating.
- The production account reaches a sending limit or is temporarily suspended.
- The recipient address is malformed, rejects mail, or reports the message as spam.
- Registration is retried and could otherwise trigger duplicate emails.
- A production action link accidentally points to a testing or local address.
- Credentials are rotated after deployment.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: The system MUST prepare one registration email after a user registration succeeds.
- **FR-002**: The system MUST NOT send a registration-success email when registration fails.
- **FR-003**: Registration emails MUST use the recipient address associated with the newly created account.
- **FR-004**: Registration email links MUST use the active environment's public application address.
- **FR-005**: Operators MUST be able to select a testing delivery configuration independently from the production delivery configuration.
- **FR-006**: The production configuration inventory MUST include the mail transport type, SMTP hostname, SMTP port, encryption or transport-security mode, authentication requirement, SMTP username, SMTP password or credential, connection timeout expectations, and certificate requirements.
- **FR-007**: The production configuration inventory MUST include the approved sender email address, sender display name, reply-to email address, reply-to display name, and any permitted-sender restrictions.
- **FR-008**: The production configuration inventory MUST include the production application address and the email addresses used for operational alerts and delivery testing.
- **FR-009**: The production readiness checklist MUST confirm the mailbox or email plan is active and identify the responsible Hostinger account, domain, mailbox, plan, and support contact.
- **FR-010**: The production readiness checklist MUST document Hostinger's applicable hourly and daily sending limits, message-size and attachment limits, recipient limits, authentication restrictions, outbound-port restrictions, and anti-abuse or suspension rules.
- **FR-011**: The production readiness checklist MUST document and verify all required sender-domain records, including domain ownership, SPF, DKIM, DMARC, and any required mail-routing records.
- **FR-012**: The production readiness checklist MUST record DNS record names, types, values, status, and expected propagation period without storing secret credentials.
- **FR-013**: Secret production values MUST be stored outside source control and MUST NOT appear in logs, rendered error messages, or user-facing documentation.
- **FR-014**: Operators MUST be able to distinguish testing, production, and fallback values for every environment-specific email setting.
- **FR-015**: Production enablement MUST be blocked until all required configuration items and sender-domain prerequisites are verified.
- **FR-016**: The system MUST record registration email delivery failures with enough context for operators to identify the affected event and reason while excluding secret values.
- **FR-017**: The release procedure MUST include a live-delivery verification to at least one external mailbox and confirmation of sender identity, content, links, and reply behavior.
- **FR-018**: The release procedure MUST include a documented rollback path for disabling live delivery or restoring the previous safe configuration.
- **FR-019**: The production configuration inventory MUST support credential rotation by identifying the credential owner, rotation procedure, and verification steps.
- **FR-020**: The production configuration inventory MUST identify which values are obtained from Hostinger, which are chosen by the application owner, and which require DNS-domain administration.

### Key Entities

- **Email Environment Configuration**: A named set of non-secret and secret-referenced values controlling email delivery for testing or production.
- **Sender Identity**: The approved sender and reply-to addresses, display names, domain, and authorization status shown to recipients.
- **Provider Configuration Inventory**: The complete set of Hostinger account, SMTP, limit, restriction, support, and DNS details required for production readiness.
- **Domain Authentication Record**: A required sender-domain record with its type, name, value, verification status, and propagation state.
- **Delivery Event**: A registration-email attempt with recipient, time, environment, outcome, and non-secret failure details.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: 100% of successful test registrations produce exactly one captured registration email with the correct recipient, content, and environment-specific links.
- **SC-002**: 100% of required production configuration and domain-readiness checklist items are verified before live delivery is enabled.
- **SC-003**: A deployment operator can switch between testing and production delivery and complete verification or rollback within 15 minutes.
- **SC-004**: At least 95% of valid registration emails sent during production acceptance testing arrive at external recipient inboxes within 5 minutes.
- **SC-005**: Zero production email credentials appear in source control, application logs, rendered errors, or user-visible documentation.
- **SC-006**: 100% of simulated delivery failures produce an operator-visible record containing the affected event and actionable non-secret failure information.

## Assumptions

- User registration already exists and this feature adds or formalizes its email notification behavior.
- Mailtrap is used only for non-production capture and testing; Hostinger shared-hosting email service is the intended initial production delivery provider.
- The application owner controls, or can request changes to, the sender domain's DNS records.
- The production sender will use an approved mailbox or identity on a domain associated with the Hostinger account.
- Exact Hostinger SMTP values, sending limits, and restrictions can vary by email product, hosting plan, server region, and account status; they must be obtained and verified for the actual account rather than assumed.
- Password reset, marketing campaigns, newsletters, and unrelated transactional emails are outside this feature's initial scope.
- Operational documentation may contain non-secret configuration values, but secret values are stored only in the production environment's protected configuration mechanism.
