# MKM Accounting Website

Production website for MKM Biuro Rachunkowe (accounting office), built as a PHP + static frontend project with SEO-focused pages and a contact form using PHPMailer.

## Tech Stack
- PHP
- HTML/CSS/JavaScript
- PHPMailer (via Composer)
- Apache (.htaccess rewrites + security headers)

## Project Structure
- `index.php` home page
- service pages: `Business.html`, `Book.html`, `expenses.html`, `payroll.html`, `tax.html`, `Contact.html`, `aboutus.html`
- reusable layout parts: `header.html`, `footer.html`
- SEO files: `robots.txt`, `sitemap.xml`, `image-sitemap.xml`
- mail handler: `mailer.php`

## Local Development (XAMPP)
1. Place the project in your `htdocs` directory.
2. Start Apache in XAMPP.
3. Install dependencies:
   ```bash
   composer install
   ```
4. Create environment file:
   ```bash
   cp .env.example .env
   ```
5. Set your SMTP credentials in `.env`.
6. Open the site at:
   - `http://localhost/account/`

## SMTP Configuration
`mailer.php` reads mail settings from environment variables:
- `SMTP_HOST`
- `SMTP_USERNAME`
- `SMTP_PASSWORD`
- `MAIL_FROM_EMAIL`
- `MAIL_REPLY_TO`
- `MAIL_BOUNCE_EMAIL`

If these are not set correctly, contact form delivery may fail or fall back to basic `mail()` behavior.

## Deployment Notes
- Ensure Apache rewrite module is enabled.
- Upload `.htaccess`, `robots.txt`, and sitemap files.
- Keep `vendor/` out of source control and run `composer install` on deployment target.
- Verify clean URLs and canonical links after deploy.

## SEO Checklist (Already Implemented)
- Canonical tags on key pages
- Internal links aligned to clean URLs
- `robots.txt` with sitemap references
- XML sitemap with primary pages
- Organization schema markup

## Security
- Do not commit `.env`.
- Never commit real SMTP passwords or API secrets.
- Rotate credentials immediately if they were ever exposed.

## License
This project is proprietary. See `LICENSE`.
