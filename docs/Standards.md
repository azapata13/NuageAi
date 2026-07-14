# Standards

## Repository Standards

- Markdown is the primary project context.
- DOCX files are references, not the main operating source.
- Keep file names clear and stable.
- Keep secrets out of Git.
- Keep workflows importable.
- Prefer reusable modules over client-specific one-offs.

## Security Standards

- Human approval first.
- Least privilege access.
- Separate clients.
- Structured logs.
- Prompt injection awareness.
- No public posting without approval.

## Workflow Standards

- Every meaningful workflow sends events to Logger.
- Every risky failure routes to Error Handler.
- Every public or direct outreach routes to approval.
- Every AI output should be structured and validated.

## Documentation Standards

Update docs when changing:

- product behavior
- agent responsibilities
- deployment process
- security rules
- workflow import process
- environment variables

