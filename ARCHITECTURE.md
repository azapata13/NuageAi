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
  -> Orchestrator
  -> Filter
  -> Analyst
  -> Researcher
  -> Strategist
  -> Writer
  -> Fact Checker
  -> Guardian
  -> Approval
  -> Publisher / CRM
  -> Logger / Cost Controller / Dashboard / Learning / Incident Agent
```

## Layers

| Layer | Role |
| --- | --- |
| Sources | Reddit, YouTube, X, Telegram, and supported Facebook API surfaces. |
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
- DEV is completely separate from production.
- All implementation work targets DEV unless a PR explicitly proposes a production change.

## Future Architecture

- HTTPS reverse proxy with Traefik or Caddy.
- Dedicated environments for sensitive clients.
- Central dashboard.
- Automated backups and restore tests.
- Secret manager.
- Client configuration registry.
- Production deployment process with manual approval gates.
