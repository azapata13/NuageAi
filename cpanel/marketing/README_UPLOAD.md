# MarketingAuto cPanel App

Upload the contents of this folder to the document root for:

```text
marketing.hortamericascanada.com
```

## Required cPanel Protection

Protect the folder with cPanel Directory Privacy before using it with real client data.

## Files

- `index.php`: Site List Intake form.
- `submit.php`: validates sources and creates AutoPostForms.
- `review.php`: human review and decision screen.
- `update.php`: saves approval decisions.
- `export.php`: downloads the request JSON.
- `data/requests`: local JSON storage.

## Security

- No secrets are stored in the code.
- `data/.htaccess` blocks direct access to stored JSON.
- `robots.txt` blocks indexing.
- This DEV app does not scrape, post, or call external platforms automatically.

