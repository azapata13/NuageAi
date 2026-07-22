# n8n Marketing Tools Architecture

This document describes the DEV n8n architecture for the two next MarketingAuto tools:

- AutoForm: approved browser automation for business contact forms.
- ReplyReddit: approved Reddit discovery and reply/message workflow.

## Shared Principles

- DEV only until a documented PR proposes production.
- Human approval is mandatory before posting, messaging, or submitting forms.
- No credential values in workflow exports.
- Use n8n credentials, environment variables, Docker Secrets, or a secret manager.
- Stop immediately on CAPTCHA, login walls, permission issues, platform blocks, or unclear compliance.
- Log every candidate, approval, attempt, success, failure, quota block, and incident.

## Shared Workflow Modules

```text
Input
  -> Normalize
  -> Compliance Check
  -> Duplicate Check
  -> Rate Controller
  -> Draft / Prepare
  -> Fact Checker
  -> Guardian
  -> AutoPostForm
  -> Approval
  -> Execution Worker
  -> Result Logger
```

## Required n8n Workflows

| Workflow | Purpose |
| --- | --- |
| `MARKETINGAUTO - AUTOFORM INTAKE` | Receives approved ContactForm CSV/export data from cPanel or manual trigger. |
| `MARKETINGAUTO - AUTOFORM WORKER` | Future VPS worker orchestration for filling approved forms. |
| `MARKETINGAUTO - REPLYREDDIT SCOUT` | Searches Reddit candidates through official/approved access. |
| `MARKETINGAUTO - REPLYREDDIT PUBLISHER` | Future approved Reddit reply/message submission. |
| `HERMES - LOGGER` | Central event logging. |
| `HERMES - ERROR HANDLER` | Error classification, retry policy, and incident escalation. |
| `HERMES - APPROVAL INBOX` | Human approval handoff. |

## AutoForm Architecture

### Inputs

- cPanel JSON export from MarketingAuto Intake.
- Later: direct webhook from the cPanel app.
- Later: PostgreSQL queue of approved AutoPostForms.

### Flow

```text
cPanel Export / Webhook
  -> Normalize ContactForm Items
  -> Required Fields Check
  -> Compliance Check
  -> Duplicate Check
  -> Rate Controller
  -> Build Browser Job
  -> Approval Gate
  -> Browser Worker
  -> Result Logger
```

### Browser Worker Responsibilities

The worker should run outside cPanel on a VPS with Docker and Playwright.

It may:

- open the approved `contact_form_url`;
- identify common fields;
- fill name, company, email, phone, subject, and message;
- capture a pre-submit state;
- submit only if approval and quotas pass;
- capture result page or error state;
- return status to n8n.

It must not:

- bypass CAPTCHA;
- bypass login;
- bypass rate limits;
- use hidden fields deceptively;
- submit when Guardian is not approved;
- submit duplicates to the same domain inside the configured cooldown.

### Status Values

- `queued`
- `approved`
- `quota_blocked`
- `duplicate_blocked`
- `captcha_blocked`
- `login_blocked`
- `filled`
- `submitted`
- `failed`
- `manual_review`

## ReplyReddit Architecture

### Inputs

- approved subreddit list;
- keywords;
- territory;
- client profile;
- offer;
- daily quota.

### Flow

```text
Schedule / Manual Trigger
  -> Reddit Search / Collector
  -> Normalize Posts and Comments
  -> Deduplicate
  -> Subreddit Rules Check
  -> Analyst Score
  -> Strategist Recommendation
  -> Writer Draft
  -> Fact Checker
  -> Guardian
  -> AutoPostForm
  -> Approval
  -> Reddit Publisher
  -> Logger
```

### Reddit Publisher Rules

Approved Reddit actions may run only after:

- official Reddit API access is configured securely;
- Reddit Developer/Data API terms and approval requirements are reviewed before implementation;
- subreddit rules allow the reply/message;
- human approval exists for the exact text;
- quota and cooldown checks pass;
- no platform issue or account risk is detected.

### Reddit Stop Conditions

- subreddit prohibits promotion;
- thread is locked or removed;
- account not authorized;
- API rate limit reached;
- duplicate or near-duplicate message;
- sensitive topic;
- Guardian blocks;
- human approval missing.

## External References To Re-check Before Build

- Reddit Data API Wiki: https://support.reddithelp.com/hc/en-us/articles/16160319875092-Reddit-Data-API-Wiki
- Reddit Data API Terms: https://redditinc.com/policies/data-api-terms
- Reddit Developer Terms: https://redditinc.com/policies/developer-terms

## Rate Controller

Recommended DEV limits:

| Tool | Limit |
| --- | --- |
| AutoForm submissions per day | 25 |
| AutoForm submissions per hour | 5 |
| AutoForm per domain cooldown | 30 days |
| ReplyReddit replies per day | 10 |
| ReplyReddit replies per subreddit per day | 2 |
| ReplyReddit direct messages | disabled by default |

## Data Stores

Use PostgreSQL for:

- campaign imports;
- candidates;
- approvals;
- duplicate history;
- quota counters;
- worker attempts;
- result events.

Use `HERMES - LOGGER` for operational logs, not as the only source of business state.

## Credentials

Future credentials:

- Reddit API credential.
- Browser worker API token.
- cPanel webhook token.
- Notification channel credential.
- CRM credential.

Credential values must never be committed.

## Implementation Order

1. Keep cPanel app as intake and approval UI.
2. Add n8n webhook to receive cPanel exports.
3. Add PostgreSQL tables for campaigns, approvals, quotas, and attempts.
4. Build AutoForm intake workflow.
5. Build ReplyReddit scout workflow with mocked Reddit data first.
6. Add VPS worker only after server/Docker is ready.
7. Add real Reddit API credentials only after safety gates are tested.
8. Add real form submission only after approval, quotas, and stop conditions are verified.
