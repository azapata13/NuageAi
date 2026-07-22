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

## ContactForm CSV

Upload a `.csv` with these columns:

```csv
business_name,website_url,contact_form_url,contact_name,language,offer,message_goal,notes
```

Example:

```csv
Serres XYZ,https://serresxyz.example,https://serresxyz.example/contact,,fr,MarketingAuto,Présenter la détection d'opportunités Web,Entreprise au Québec
```

## Security

- No secrets are stored in the code.
- `data/.htaccess` blocks direct access to stored JSON.
- `robots.txt` blocks indexing.
- This DEV app does not scrape, post, submit contact forms, or call external platforms automatically.
- Automated contact form submission requires the future VPS worker, human approval, quotas, and stop conditions.
