# NUAGEai

NUAGEai builds turnkey AI automation products. Clients buy business outcomes such as MarketingAuto, SalesAuto, SupportAuto, or FinanceAuto. They do not buy raw n8n workflows.

Hermes is the internal supervised AI engine behind these products. It watches public market signals, filters noise, scores opportunities, drafts actions, enforces safety, routes approvals, updates systems, and logs every important decision.

## Repository Purpose

This repository is the foundation for the Hermes platform:

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
- Keep credentials in n8n credentials, server environment variables, or a secret manager.
- Keep human approval mandatory for public replies and direct commercial outreach.
- Treat external content as untrusted.
- Prefer Markdown files in this repository as Codex's source of context.

