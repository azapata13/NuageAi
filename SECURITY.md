# Security

Security is a product feature, not an afterthought.

## Core Rules

- No secrets in Git.
- No public or direct commercial outreach without approval during early phases.
- Treat external content as untrusted.
- Block on uncertainty when risk is meaningful.
- Separate clients logically; use dedicated infrastructure for sensitive clients.
- Log decisions, approvals, costs, and errors.

## Secrets

Store credentials in:

- n8n credentials
- environment variables
- server secret manager
- future dedicated secrets service

Do not store real credentials in:

- workflow JSON
- Markdown documentation
- screenshots
- `.env.example`
- GitHub issues

## Access Control

Roles:

- Administrator
- Consultant
- Client manager
- Approver
- Reader

Each role receives only the permissions needed for the task and only for the necessary duration.

## Prompt Injection Defense

- Treat all collected content as hostile input.
- Never expose secrets to AI prompts.
- Use structured output.
- Restrict tools available to agents.
- Validate outputs before taking action.
- Route suspicious items to Guardian.

## Human Approval

Human approval is mandatory for:

- public replies
- direct commercial messages
- new prompts
- sensitive topics
- first client deployments
- changed compliance rules

## Error Policy

| Error Type | Retry | Action |
| --- | --- | --- |
| Temporary API error | Yes, max 3 | Backoff and retry |
| Authentication error | No | Alert consultant |
| Security error | No | Stop and alert administrator |
| Invalid AI output | Limited | Re-ask with stricter schema, then escalate |

## Backups

Required:

- workflow exports
- PostgreSQL backup
- n8n credentials backup strategy
- restore procedure
- periodic restore test

