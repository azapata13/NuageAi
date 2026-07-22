# ContactForm Outreach

ContactForm Outreach is the MarketingAuto module for approved outreach through public business contact forms.

## Goal

The user provides a CSV of businesses and contact form URLs. MarketingAuto prepares personalized form messages, routes them to human approval, and later a VPS worker can fill and submit approved forms while respecting anti-spam limits.

## Current cPanel DEV Scope

The cPanel app can:

- import a CSV campaign;
- generate draft messages;
- create AutoPostForm records;
- record human approval decisions;
- export JSON for later automation.

It does not submit contact forms automatically.

## Future VPS Automation Scope

The future VPS worker can:

- open approved contact form URLs;
- detect common fields such as name, company, email, phone, subject, and message;
- fill fields with approved content;
- stop on CAPTCHA, login, payment walls, or suspicious pages;
- submit only after human approval and quota checks;
- log every attempt, success, failure, and block reason.

## Required CSV Columns

```csv
business_name,website_url,contact_form_url,contact_name,language,offer,message_goal,notes
```

## Anti-Spam Rules

- Human approval is required before submission.
- Daily and hourly quotas are required.
- Duplicate submissions to the same business or domain are blocked.
- CAPTCHA, login, account creation, or hidden protections stop automation.
- The message must be relevant and personalized.
- No deceptive identity or fake affiliation.
- No scraping of private data.
- No sensitive categories without explicit review.
- Every submission attempt must be logged.

## Recommended Quotas for DEV

| Scope | Limit |
| --- | --- |
| Per campaign | 25 approved submissions per day |
| Per domain | 1 submission every 30 days |
| Per hour | 5 submissions |
| Failed attempts | Stop after 3 consecutive failures |
| CAPTCHA/login | Stop immediately |

## Future Worker Flow

```text
Approved AutoPostForm
  -> Rate Controller
  -> Duplicate Check
  -> Browser Worker
  -> Field Detector
  -> Fill Fields
  -> Pre-submit Screenshot / Review Log
  -> Submit
  -> Result Logger
```

## Stop Conditions

- CAPTCHA detected
- login required
- hidden or deceptive fields
- terms forbid automated submission
- form topic is not commercial/general contact
- source was previously contacted
- quota exceeded
- Guardian status is not approved

