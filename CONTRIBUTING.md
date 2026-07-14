# Contributing

## Working Principles

- Markdown files are the primary context for Codex and contributors.
- Keep DOCX files as source references only when needed; do not rely on them as the operating memory.
- Keep secrets out of Git.
- Keep changes scoped and reviewable.
- Prefer reusable platform improvements over one-off client customizations.

## Branches

Use branches for meaningful changes:

```text
codex/foundation
codex/docker
codex/n8n-logger
codex/security
```

## Pull Requests

Every PR should include:

- what changed
- why it changed
- how it was tested
- security impact
- deployment notes

## Documentation

Update relevant Markdown files when behavior changes:

- `VISION.md`
- `ROADMAP.md`
- `ARCHITECTURE.md`
- `AGENTS.md`
- `SECURITY.md`
- `docs/*.md`

## n8n Workflows

- Export workflows as JSON into `workflows/n8n`.
- Do not include credentials in workflow exports.
- Keep workflow names stable.
- Prefer central Logger and Error Handler workflows.

## Docker

- Keep `.env.example` safe and generic.
- Keep real `.env` files untracked.
- Prefer repeatable commands in documentation.

