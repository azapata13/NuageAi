# Architecture

## High-Level Stack

```text
NUAGEai
├── Hermes
├── Atlas
├── Sentinel
├── Logger
├── Dashboard
├── Connectors
├── Templates
└── Products
    ├── MarketingAuto
    ├── SalesAuto
    ├── SupportAuto
    └── FinanceAuto
```

## Hermes Flow

```text
Sources
  -> n8n Collectors
  -> Filter
  -> Analyst
  -> Researcher
  -> Writer
  -> Guardian
  -> Approval
  -> Publisher / CRM
  -> Logger / Dashboard / Learning
```

## Layers

| Layer | Role |
| --- | --- |
| Sources | Reddit, LinkedIn, YouTube, forums, news, Facebook, and authorized public channels. |
| n8n Collection | API connection, scheduled triggers, pagination, normalization. |
| Filtering | Deduplication, keyword rules, freshness, language, territory, spam rejection. |
| Hermes Agents | Orchestration, scoring, enrichment, writing, security, approval routing. |
| Storage | PostgreSQL for logs and structured operational state. |
| Queue | Redis for n8n queue execution. |
| Approval | Human review before public posting or direct commercial outreach. |
| CRM | Create/update contacts, opportunities, tasks, and source history. |
| Measurement | Cost, tokens, errors, approvals, conversions, and learning signals. |

## Current DEV Implementation

- n8n container for editor and webhooks.
- n8n worker for queued executions.
- PostgreSQL for n8n and Hermes logs.
- Redis for n8n queue mode.
- Importable workflow JSON files in `workflows/n8n`.

## Future Architecture

- HTTPS reverse proxy with Traefik or Caddy.
- Dedicated environments for sensitive clients.
- Central dashboard.
- Automated backups and restore tests.
- Secret manager.
- Client configuration registry.

