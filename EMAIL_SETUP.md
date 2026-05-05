# Email & Feedback System Setup Guide

## Overview
Your website now has a complete email notification and customer feedback system. Here's what has been implemented:

### Features:
1. **Automatic Email Notifications**
   - Customers receive a confirmation email when they submit the contact form
   - You receive a notification email with their inquiry details

2. **Admin Feedback System**
   - View all customer inquiries in the admin panel
   - Click "Reply" on any inquiry to send a response
   - Customers receive your reply via email

3. **Status Tracking**
   - Inquiries automatically change status to "replied" when you respond

---

## Setup Instructions

### Step 1: Configure Your Email Settings

Open `config.php` and update these settings:

```php
define('ADMIN_EMAIL', 'your-email@gmail.com');              // YOUR EMAIL
define('ADMIN_NAME', 'HD Media Admin');                     // Your business name
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');                // YOUR EMAIL
define('SMTP_PASSWORD', 'your-app-password');               // See Step 2
define('USE_SMTP', false);                                  // Use false for basic mail()
```

### Step 2: Get Gmail App Password (if using Gmail)

1. Go to: https://myaccount.google.com/security
2. Enable "2-Step Verification" if not already enabled
3. Go to "App passwords"
4. Select "Mail" and "Windows Computer"
5. Google will generate a 16-character password
6. Copy and paste this in `SMTP_PASSWORD` in config.php

**Example:** `icod jdne ueid wkdi`

### Step 3: Set Up Email Using PHP mail() Function (Easiest)

If you're using a shared hosting provider, they usually have mail() configured:

1. Keep `USE_SMTP` set to `false`
2. Make sure `php.ini` has these settings:
   ```
   sendmail_path = "sendmail -t -i"
   ```

Contact your hosting provider if mail() doesn't work.

### Step 4: Database Setup

Make sure these tables exist in your `HD_Media` database:

```sql
-- Contact Inquiries Table
CREATE TABLE IF NOT EXISTS contact_inquiries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255),
    message LONGTEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    inquiry_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings Table (should already exist)
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    service_type VARCHAR(100),
    event_date VARCHAR(50),
    location VARCHAR(255),
    budget VARCHAR(50),
    additional_notes LONGTEXT,
    status VARCHAR(20) DEFAULT 'pending',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials Table (should already exist)
CREATE TABLE IF NOT EXISTS testimonials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_name VARCHAR(100) NOT NULL,
    service_used VARCHAR(100),
    rating INT,
    testimonial_text LONGTEXT,
    approved BOOLEAN DEFAULT FALSE,
    date_submitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## How It Works

### For Customers:

1. **Customer fills contact form** → Receives confirmation email
2. **You send a reply** → Customer receives your response
3. **Email shows original inquiry** → Customer can see the full context

### For You (Admin):

1. **Go to:** `http://localhost/Website%20Photo-guru/Home/admin.php`
2. **Scroll to Contact Inquiries section**
3. **Click "Reply" button** on any inquiry
4. **Type your response** in the modal
5. **Click "Send Reply"** → Email sent to customer

---

## Files Modified/Created:

1. **config.php** - Added email configuration and sendEmail() function
2. **process_contact.php** - Sends confirmation & admin notification emails
3. **admin.php** - Enhanced with feedback/reply system UI
4. **send_reply.php** - NEW - Handles sending replies to customers
5. **Contact.html** - Updated form action to process_contact.php

---

## Testing the System

### Test Email Sending:

1. Go to Contact page: `/Pages/Contact.html`
2. Fill out the form and submit
3. Check:
   - Your email for a notification from the customer
   - Customer's email (use a test email) for confirmation
4. In admin panel, click "Reply" and send a response
5. Verify the reply email was received

### Troubleshooting:

| Problem | Solution |
|---------|----------|
| Emails not sending | Check `ADMIN_EMAIL` is set correctly in config.php |
| "Access Denied" errors | Enable "Less secure apps" in Gmail settings (if using Gmail) |
| Can't see inquiries in admin | Make sure database table `contact_inquiries` exists |
| Reply email not received | Check SMTP/mail() configuration |

---

## Email Configuration Options

### Option 1: Using mail() (Easiest - Most Hosting)
```php
define('USE_SMTP', false);
// Just set ADMIN_EMAIL and ADMIN_NAME
```

### Option 2: Using SMTP (Gmail/Outlook)
```php
define('USE_SMTP', true);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
```

---

## Security Notes

- Always use HTTPS in production
- Never commit actual email credentials to version control
- Store `SMTP_PASSWORD` securely (use environment variables in production)
- The email configuration in config.php is already included in .gitignore

---

## Next Steps

1. Update config.php with your email
2. Test by submitting a contact form
3. Reply to a test inquiry from admin panel
4. Verify emails are sent and received

**Need help?** Check your hosting provider's email setup guide or contact their support.
