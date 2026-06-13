# PHPMailer Installation Guide for MKM Biuro Rachunkowe

## Option 1: Using Composer (Recommended)

1. Open terminal in your project directory:
```bash
cd c:\xampp\htdocs\account
```

2. Install PHPMailer:
```bash
composer require phpmailer/phpmailer
```

## Option 2: Manual Download

1. Download PHPMailer from: https://github.com/PHPMailer/PHPMailer/releases
2. Extract to folder: `c:\xampp\htdocs\account\PHPMailer\`
3. Make sure these files exist:
   - `PHPMailer/src/PHPMailer.php`
   - `PHPMailer/src/SMTP.php`
   - `PHPMailer/src/Exception.php`

## SMTP Configuration

Edit `mailer.php` and update these settings:

### For Gmail SMTP:
```php
'host' => 'smtp.gmail.com',
'port' => 587,
'username' => 'your-email@gmail.com',
'password' => 'your-app-password',  // Use App Password, not regular password
```

### For Other Email Providers:

**Yahoo:**
```php
'host' => 'smtp.mail.yahoo.com',
'port' => 587,
```

**Outlook/Hotmail:**
```php
'host' => 'smtp-mail.outlook.com',
'port' => 587,
```

**Your Hosting Provider:**
Contact your hosting provider for SMTP settings.

## Gmail Setup Steps:

1. Enable 2-Factor Authentication
2. Generate App Password:
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Generate password for "Mail"
   - Use this password in mailer.php

## Testing:

Your contact form will now send:
1. ✅ Professional HTML email to you
2. ✅ Auto-reply confirmation to customer
3. ✅ Error handling and logging

## Troubleshooting:

- Check XAMPP has `php_openssl` extension enabled
- Verify firewall allows outbound SMTP connections
- Check error logs in browser console