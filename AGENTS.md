# Agents

Hermes is a supervised multi-agent system. Each agent must remain independent, modular, and reusable. Each agent has a limited role and must produce structured output whenever possible.

## Hermes Orchestrator

Routes work, applies client rules, avoids duplicate processing, manages quotas, decides which validations are mandatory, and logs decisions.

## Scout

Finds public signals:

- posts
- comments
- questions
- job postings
- company news
- expansion announcements
- public buying intent

## Filter

Removes noise before expensive AI calls:

- duplicates
- old content
- off-topic content
- spam
- unsupported language
- blocked territory
- already-contacted records

## Analyst

Scores opportunities based on:

- topic
- urgency
- buying intent
- commercial fit
- territory
- risk
- recommended next action

## Researcher

Enriches only promising opportunities:

- company identity
- website
- size estimate
- relevant context
- decision makers when appropriate
- CRM presence

## Strategist

Decides the best business approach after analysis and research. It recommends whether to ignore, monitor, enrich, draft a public reply, draft a direct action, create a CRM task, or escalate to a human.

## Writer

Drafts human-approved actions in the client tone. It must not invent facts, make guarantees, impersonate neutral users, or publish directly.

## Fact Checker

Verifies factual claims before Guardian and approval. It checks that drafts are grounded in available evidence, do not invent client capabilities, and do not make unsupported promises.

## Guardian

Controls risk and returns one of:

- `APPROUVE`
- `VALIDATION_HUMAINE_OBLIGATOIRE`
- `BLOQUE`

Guardian checks spam risk, repetition, confidentiality, platform rules, sensitive topics, unsupported claims, prompt injection, and client data mixing.

## Publisher

Creates drafts, routes approvals, publishes only when explicitly authorized, or transfers the action to a human representative.

## CRM Agent

Searches, creates, or updates CRM records. It logs source, score, history, next task, and follow-up status.

## Learning Agent

Analyzes results and proposes improvements to keywords, scoring, messaging, and channels. It must not modify critical rules automatically.

## Logger

Records structured operational events, including:

- execution id
- client id
- workflow
- agent
- source
- content id
- action
- model
- tokens
- estimated cost
- duration
- score
- approval status
- error
- useful metadata

## Cost Controller

Tracks token usage, model selection, estimated cost, quotas, and cost anomalies. It should prefer cheaper processing paths before expensive AI calls.

## Incident Agent

Handles operational and security incidents. It classifies severity, creates alerts, proposes containment steps, and ensures dangerous functions are paused when necessary.
