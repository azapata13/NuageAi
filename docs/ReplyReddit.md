# ReplyReddit

ReplyReddit is the future MarketingAuto module for finding relevant Reddit conversations and preparing compliant replies or messages.

## Goal

Use Reddit as a source of public commercial signals, then prepare helpful, relevant replies for human-approved outreach.

## Core Rule

ReplyReddit must use official Reddit API access or approved workflows. It must not spam, impersonate users, bypass rate limits, or automate prohibited behavior.

## Allowed DEV Behavior

- Search approved subreddits and keywords.
- Score relevant public posts or comments.
- Generate a proposed reply.
- Run Fact Checker and Guardian.
- Create AutoPostForm records.
- Require human approval before posting or messaging.
- Respect quotas and subreddit rules.

## Future Automation Behavior

After VPS setup, approved replies may be submitted only if:

- Reddit API credentials are configured securely;
- subreddit rules permit the action;
- the reply is relevant and transparent;
- the user has approved the exact message;
- rate limits and campaign quotas pass;
- no CAPTCHA, login issue, or platform block occurs.

## Anti-Spam Rules

- No generic mass replies.
- No repeated message templates without personalization.
- No direct messages unless clearly appropriate and approved.
- No posting in subreddits that prohibit promotion.
- No ban evasion or account rotation.
- Log every candidate, approval, submission, failure, and moderation risk.

## Future Workflow

```text
Subreddit/keyword list
  -> Reddit Collector
  -> Filter
  -> Analyst
  -> Strategist
  -> Writer
  -> Fact Checker
  -> Guardian
  -> AutoPostForm
  -> Approval
  -> Reddit Publisher
  -> Logger / Incident Agent
```

## n8n Architecture

See [n8n-marketing-tools.md](n8n-marketing-tools.md) and `workflows/n8n/MARKETINGAUTO_REPLYREDDIT_SCOUT.json`.
