# Site List Intake

Site List Intake is the MarketingAuto entry point where a user provides the places the system should evaluate.

The user can submit websites, URLs, communities, channels, profiles, search pages, forums, or platform links. MarketingAuto then decides what can be collected automatically, what must use an official API, what must be handled in assisted mode, and what must be blocked.

## Purpose

Turn a raw list of target sources into compliant, normalized work items for Hermes.

## User Input

The user can provide:

- website URLs
- Reddit communities or posts
- YouTube channels, videos, or search terms
- X profiles, posts, or search terms
- Telegram channels or public groups
- Facebook pages or API-supported assets
- forums or public communities
- keywords and territories
- client-specific exclusions

## Processing Flow

```text
User source list
  -> normalize URLs and labels
  -> classify source type
  -> check platform compliance
  -> choose access mode
  -> create source tasks
  -> run allowed collectors or assisted workflow
  -> pass findings to Filter / Analyst
  -> generate AutoPostForm when an action is worth reviewing
```

## Access Modes

| Mode | Meaning |
| --- | --- |
| `official_api` | Use an official API or approved integration. |
| `public_crawl_allowed` | Limited public-page collection when permitted and respectful. |
| `assisted_mode` | Prepare human action only; no automated posting or restricted access. |
| `manual_review` | Human must inspect before any collection or action. |
| `blocked` | Do not collect or act because the source is restricted or risky. |

## Source Classification

Each submitted source should become a normalized record:

```json
{
  "source_id": "",
  "client_id": "",
  "input_url": "",
  "normalized_url": "",
  "platform": "website|reddit|youtube|x|telegram|facebook|forum|other",
  "source_type": "site|profile|community|post|video|search|page|group|channel",
  "access_mode": "official_api|public_crawl_allowed|assisted_mode|manual_review|blocked",
  "territory": "",
  "keywords": [],
  "compliance_notes": [],
  "status": "pending|approved|blocked|needs_review",
  "metadata": {}
}
```

## Compliance Rules

The scraping and collection rules do not change:

- Prefer official APIs.
- Do not bypass authentication, permissions, rate limits, robots rules, platform terms, or technical protections.
- Reddit, YouTube, X, and Telegram must use authorized access or allowed workflows.
- Facebook is supported only through official APIs and allowed functionality.
- Facebook groups not accessible through official APIs are assisted mode only.
- If a source is unclear or risky, route it to manual review.

## Output

Site List Intake produces source tasks for:

- Scout
- platform-specific collectors
- assisted review
- AutoPostForm preparation
- CRM task creation
- Logger

## n8n Implementation

The DEV workflow should:

1. Receive a list of sources through a webhook or manual trigger.
2. Normalize and deduplicate URLs.
3. Classify each source.
4. Apply compliance rules.
5. Store source tasks in PostgreSQL.
6. Log intake results.
7. Route approved tasks to collectors.
8. Route assisted tasks to AutoPostForm or manual review.
9. Route blocked tasks to Logger with reasons.

## Example

Input:

```json
{
  "client_id": "demo",
  "sources": [
    "https://www.reddit.com/r/greenhouses/",
    "https://www.youtube.com/@example-channel",
    "https://example.com/blog",
    "https://www.facebook.com/groups/example"
  ],
  "keywords": ["greenhouse automation", "irrigation monitoring"],
  "territory": "Canada"
}
```

Output:

```json
[
  {
    "platform": "reddit",
    "access_mode": "official_api",
    "status": "approved"
  },
  {
    "platform": "youtube",
    "access_mode": "official_api",
    "status": "approved"
  },
  {
    "platform": "website",
    "access_mode": "public_crawl_allowed",
    "status": "needs_review"
  },
  {
    "platform": "facebook",
    "access_mode": "assisted_mode",
    "status": "needs_review"
  }
]
```

