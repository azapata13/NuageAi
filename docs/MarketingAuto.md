# MarketingAuto

MarketingAuto is the first commercial product powered internally by Hermes.

## Goal

Help clients detect opportunities on the Web, qualify relevant conversations, prepare natural responses, and assist sales teams.

Hermes should remain internal language. Client-facing communication should focus on MarketingAuto and its business outcomes.

## MVP Platforms

- Reddit
- YouTube
- X
- Telegram

Facebook is supported only through official APIs and allowed functionality. Facebook groups that are not accessible through official APIs are handled in assisted mode only, meaning response preparation and human action support without unauthorized automation.

## MVP Capabilities

- accept a user-provided list of sites, URLs, communities, or sources
- classify each source by platform, access method, and compliance mode
- monitor selected sources
- detect relevant conversations
- score commercial intent
- draft responses or follow-up actions
- prepare an AutoPostForm for human review and assisted posting
- require human approval
- log cost and decisions
- create CRM tasks or records
- produce weekly performance reports

## Human Review

All public replies and direct commercial messages require approval during the early product phase.

## Output Examples

- lead signal summary
- recommended response draft
- AutoPostForm review page
- CRM task
- daily digest
- weekly opportunity report

## cPanel DEV App

The first lightweight DEV implementation is available in `cpanel/marketing`. It is designed for shared cPanel hosting and does not require Docker.

This app:

- accepts a site/source list;
- imports ContactForm Outreach CSV campaigns;
- classifies source type and access mode;
- creates AutoPostForm review records;
- stores request data as local JSON;
- allows human approval decisions;
- exports JSON for later n8n/Hermes processing.

It does not scrape, publish, submit contact forms, or call external platform APIs automatically.

Future VPS modules will handle approved `ContactForm Outreach` and `ReplyReddit` automation with quotas, anti-spam controls, and stop conditions.
