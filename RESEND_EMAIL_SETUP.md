# Resend Email Setup Guide

This guide explains how to set up and use the Resend email service in this Laravel 10 application.

## Files Created

1. **Mailable**: `app/Mail/ReminderEmail.php`
2. **Email View**: `resources/views/emails/reminder.blade.php`
3. **Route**: Added to `routes/web.php` at `/test-email`
4. **Mail Config**: Updated `config/mail.php` with Resend mailer configuration

## Environment Variables

Add the following to your `.env` file:

```env
# Resend Configuration
RESEND_API_KEY=your_resend_api_key_here
```

**Note**: The code uses Resend API directly (not SMTP), so you only need the API key.

## How to Use

1. **Get your Resend API Key**:
   - Sign up at https://resend.com
   - Go to API Keys section
   - Create a new API key
   - Copy the API key

2. **Add to .env**:
   ```env
   RESEND_API_KEY=re_xxxxxxxxxxxxx
   ```

3. **Test the email**:
   - Visit: `http://your-domain.com/test-email`
   - The email will be sent to: `abdelrahman.yousef@hadaf-hq.com`

## Email Details

- **From**: no-reply@eco-app.eco-propertiesglobal.co.uk (Eco Rent)
- **Subject**: Test Email
- **Content**: Simple HTML email with heading and paragraph

## Customization

To customize the email:
- Edit `app/Mail/ReminderEmail.php` to change sender, subject, etc.
- Edit `resources/views/emails/reminder.blade.php` to change email content
- Update the route in `routes/web.php` to change recipient or add parameters

## Notes

- The code uses Resend PHP SDK (`resend/resend-php`) to send emails via API
- Make sure your domain is verified in Resend dashboard
- The sender email must be a verified domain in Resend
- The package `resend/resend-php` is required (already installed via composer)
