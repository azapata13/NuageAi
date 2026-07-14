# Infrastructure

## DEV Stack

- Ubuntu server
- Docker
- Docker Compose
- n8n
- PostgreSQL
- Redis
- GitHub

DEV is completely separate from production. All current development targets DEV only.

## Repository Directories

```text
docker/
infrastructure/
workflows/
prompts/
api/
database/
security/
scripts/
tests/
documentation/
docs/
examples/
```

## Current Services

| Service | Purpose |
| --- | --- |
| n8n | Workflow editor, triggers, webhooks. |
| n8n-worker | Queue execution worker. |
| PostgreSQL | n8n data and Hermes logs. |
| Redis | n8n queue backend. |

## Future Services

- reverse proxy with HTTPS
- metrics dashboard
- backups
- client configuration service
- alerting channel
- secret manager

## Production Boundary

Production must not be modified automatically. Any production architecture change requires a documented Pull Request, explicit review, and manual deployment approval.
