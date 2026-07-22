# AutoPostForm

AutoPostForm is a MarketingAuto module for assisted publishing and response review. It is not a scraping module and it must not bypass platform rules.

## Purpose

AutoPostForm turns a qualified opportunity and draft response into a structured human review form. The form helps an approver understand the context, verify the recommendation, edit the response, and choose the next action.

## Core Rule

AutoPostForm never publishes automatically in DEV. It prepares, validates, records approval, and hands off only when the required human decision is complete.

## Inputs

- client id
- source platform
- source URL
- content id
- original conversation excerpt
- opportunity score
- Analyst summary
- Researcher enrichment
- Strategist recommendation
- Writer draft
- Fact Checker result
- Guardian status
- platform-specific constraints
- CRM match or task target

## Form Sections

1. Opportunity summary
2. Source and platform context
3. Risk and compliance notes
4. Draft response
5. Fact-check status
6. Guardian decision
7. Required approver
8. Approval decision
9. Final edited response
10. Next action

## Approval Decisions

- `approve_for_manual_post`
- `approve_for_crm_task`
- `request_changes`
- `reject`
- `block_and_escalate`

## Platform Rules

AutoPostForm must preserve the same platform rules as the scraping and collector layer:

- Reddit, YouTube, X, and Telegram must use authorized API access or allowed workflows.
- Facebook is supported only through official APIs and allowed functionality.
- Facebook groups not accessible through official APIs are assisted mode only.
- Public or direct commercial outreach requires human approval.
- No credentials, tokens, cookies, or passwords are stored in the form.

## Data Model

Suggested fields:

```json
{
  "autopost_form_id": "",
  "client_id": "",
  "platform": "reddit|youtube|x|telegram|facebook|other",
  "source_url": "",
  "content_id": "",
  "score": 0,
  "guardian_status": "APPROUVE|VALIDATION_HUMAINE_OBLIGATOIRE|BLOQUE",
  "fact_check_status": "verified|needs_review|failed",
  "draft_message": "",
  "edited_message": "",
  "approval_decision": "approve_for_manual_post|approve_for_crm_task|request_changes|reject|block_and_escalate",
  "approver": "",
  "approved_at": "",
  "next_action": "manual_post|crm_task|revise|none|incident",
  "metadata": {}
}
```

## n8n Implementation

The DEV workflow should:

1. Receive a qualified opportunity from Guardian or Approval.
2. Build a normalized AutoPostForm payload.
3. Store the form record in PostgreSQL.
4. Notify the approver through the selected channel.
5. Wait for a human decision.
6. Log the decision through `HERMES - LOGGER`.
7. Route approved items to Publisher or CRM Agent.
8. Route rejected or blocked items to Logger or Incident Agent.

## Security

- No automatic production action.
- No secrets in the form payload.
- No credentials in exported workflows.
- Every form decision must be logged.
- Every platform action must remain compliant with the platform's allowed functionality.

