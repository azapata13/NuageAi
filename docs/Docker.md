# Docker

## Local Start

```bash
cp .env.example .env
docker compose --env-file .env -f docker/docker-compose.yml up -d
```

## Check Services

```bash
docker compose --env-file .env -f docker/docker-compose.yml ps
```

## Stop Services

```bash
docker compose --env-file .env -f docker/docker-compose.yml down
```

## Logs

```bash
docker compose --env-file .env -f docker/docker-compose.yml logs -f n8n
```

## Initialize Hermes Logs

```bash
docker compose --env-file .env -f docker/docker-compose.yml exec -T postgres \
  sh -lc 'psql -U "$POSTGRES_USER" -d "$POSTGRES_DB"' < database/001_hermes_logs.sql
```

## Production Notes

- Replace all default passwords.
- Use a stable `N8N_ENCRYPTION_KEY`.
- Add HTTPS before serious use.
- Add backups before storing client data.
- Keep `.env` out of Git.

