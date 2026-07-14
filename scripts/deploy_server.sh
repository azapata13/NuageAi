#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/opt/nuageai/hermes-platform}"
COMPOSE_FILE="${COMPOSE_FILE:-docker/docker-compose.yml}"

cd "$APP_DIR"

git fetch origin main
git reset --hard origin/main

if [ ! -f .env ]; then
  cp .env.example .env
  echo "Created .env from .env.example. Edit secrets before exposing n8n publicly."
fi

docker compose --env-file .env -f "$COMPOSE_FILE" pull
docker compose --env-file .env -f "$COMPOSE_FILE" up -d
docker compose --env-file .env -f "$COMPOSE_FILE" ps

