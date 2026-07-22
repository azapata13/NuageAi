# NUAGEai Hermes Platform

This repository contains the first deployable foundation for Hermes, the internal AI orchestration engine behind NUAGEai products.

## What is included

- Docker Compose for n8n, PostgreSQL, and Redis.
- Importable n8n workflows:
  - `HERMES - MAIN PIPELINE`
  - `HERMES - LOGGER`
  - `HERMES - ERROR HANDLER`
  - `HERMES - APPROVAL INBOX`
- PostgreSQL schema for centralized logs.
- Baseline agent prompts.
- GitHub Actions deployment over SSH, disabled until explicitly enabled.

## Local start

```bash
cp .env.example .env
docker compose --env-file .env -f docker/docker-compose.yml up -d
```

n8n will be available at:

```text
http://localhost:5678
```

Before any public deployment, edit `.env` and replace every `change-me` value.

## Import workflows

In n8n:

1. Open the editor.
2. Import each JSON file from `workflows/n8n`.
3. Configure credentials for PostgreSQL and external services.
4. Activate `HERMES - LOGGER` first.
5. Activate `HERMES - ERROR HANDLER`.
6. Test `HERMES - MAIN PIPELINE` manually before activating its schedule.

## Database logs

Run these once against the PostgreSQL database:

```bash
docker compose --env-file .env -f docker/docker-compose.yml exec -T postgres \
  sh -lc 'psql -U "$POSTGRES_USER" -d "$POSTGRES_DB"' < database/001_hermes_logs.sql

docker compose --env-file .env -f docker/docker-compose.yml exec -T postgres \
  sh -lc 'psql -U "$POSTGRES_USER" -d "$POSTGRES_DB"' < database/002_marketingauto_intake.sql
```

## Server deployment

The server deploy script is available at:

```text
scripts/deploy_server.sh
```

A GitHub Actions template is available at:

```text
examples/github-actions-deploy.yml
```

To enable automatic deploys on push, copy it to `.github/workflows/deploy.yml` with a GitHub token that has the `workflow` scope. The workflow deploys on push to `main` only when this repository variable is set:

```text
ENABLE_SERVER_DEPLOY=true
```

Required GitHub secrets:

```text
SSH_HOST
SSH_USER
SSH_KEY
DEPLOY_PATH
```

On the server, `DEPLOY_PATH` should point to the cloned repository, for example:

```text
/opt/nuageai/hermes-platform
```

The deploy script performs:

```bash
git fetch origin main
git reset --hard origin/main
docker compose --env-file .env -f docker/docker-compose.yml pull
docker compose --env-file .env -f docker/docker-compose.yml up -d
```

## Security rules

- Never commit `.env` or credentials.
- Keep all client credentials in n8n credentials or a secret manager.
- Keep human approval mandatory for public replies and direct commercial outreach.
- Treat all external content as untrusted input.
- Log every meaningful decision, cost, error, and approval status.
