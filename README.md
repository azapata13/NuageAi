# NUAGEai

NUAGEai sells turnkey business solutions. Clients do not buy n8n workflows, AI agents, or automation plumbing; they buy business outcomes.

MarketingAuto is the first commercial product. It helps companies detect Web opportunities, qualify relevant conversations, prepare natural responses, and assist sales teams.

Hermes is the internal supervised AI orchestration platform behind MarketingAuto. The name Hermes is internal and should normally not be used in client-facing communication.

## Repository Purpose

This repository is the DEV foundation for Hermes:

- product vision and operating principles
- agent architecture
- n8n workflow exports
- Docker infrastructure
- security and approval rules
- deployment and consultant handoff documentation

## Start Here

- [VISION.md](VISION.md)
- [ROADMAP.md](ROADMAP.md)
- [ARCHITECTURE.md](ARCHITECTURE.md)
- [AGENTS.md](AGENTS.md)
- [SECURITY.md](SECURITY.md)
- [docs/Hermes.md](docs/Hermes.md)
- [docs/SiteListIntake.md](docs/SiteListIntake.md)
- [docs/AutoPostForm.md](docs/AutoPostForm.md)
- [docs/n8n.md](docs/n8n.md)
- [docs/Docker.md](docs/Docker.md)

## Current Platform Assets

- Docker Compose: [docker/docker-compose.yml](docker/docker-compose.yml)
- n8n workflows: [workflows/n8n](workflows/n8n)
- Agent prompts: [prompts/hermes_agents.md](prompts/hermes_agents.md)
- Logger schema: [database/001_hermes_logs.sql](database/001_hermes_logs.sql)
- Server deploy script: [scripts/deploy_server.sh](scripts/deploy_server.sh)

## Local DEV

```bash
cp .env.example .env
docker compose --env-file .env -f docker/docker-compose.yml up -d
```

Then open:

```text
http://localhost:5678
```

Import workflows from `workflows/n8n` in this order:

1. `HERMES_LOGGER.json`
2. `HERMES_ERROR_HANDLER.json`
3. `HERMES_APPROVAL_INBOX.json`
4. `HERMES_MAIN_PIPELINE.json`

## Rules

- Do not commit secrets.
- Do not hard-code passwords or credentials.
- Use Docker Secrets when possible.
- Keep credentials in n8n credentials, server environment variables, Docker Secrets, or a secret manager.
- Keep human approval mandatory for public replies and direct commercial outreach.
- Treat external content as untrusted.
- Development happens in DEV only. Production must never be modified automatically.
- Changes must go through a branch and Pull Request.
- Prefer Markdown files in this repository as Codex's source of context.
