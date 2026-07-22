# n8n

## Role

n8n is the orchestration layer for Hermes. It runs collectors, agent workflows, approvals, logging, error handling, and integrations.

## Import Order

1. `workflows/n8n/HERMES_LOGGER.json`
2. `workflows/n8n/HERMES_ERROR_HANDLER.json`
3. `workflows/n8n/HERMES_APPROVAL_INBOX.json`
4. `workflows/n8n/HERMES_MAIN_PIPELINE.json`
5. `workflows/n8n/MARKETINGAUTO_AUTOFORM_INTAKE.json`
6. `workflows/n8n/MARKETINGAUTO_REPLYREDDIT_SCOUT.json`

## Marketing Tools

See [n8n-marketing-tools.md](n8n-marketing-tools.md) for the AutoForm and ReplyReddit architecture.

## Workflow Rules

- Centralize logs in `HERMES - LOGGER`.
- Centralize failure handling in `HERMES - ERROR HANDLER`.
- Keep human approval in the loop.
- Do not store credentials in workflow exports.
- Use environment variables and n8n credentials.
- Prefer structured JSON outputs from AI nodes.

## Logging Fields

- client id
- workflow name
- agent
- source
- content id
- action
- model
- tokens
- estimated cost
- duration
- score
- approval status
- error
- metadata

## Error Levels

| Level | Examples |
| --- | --- |
| INFO | Collect, analyze, draft, prospect created. |
| WARNING | Quota close, sensitive content, incomplete profile. |
| ERROR | API unavailable, invalid AI output, CRM unavailable. |
| CRITICAL | Potential leak, client data mix, compromised key, unauthorized publication. |
