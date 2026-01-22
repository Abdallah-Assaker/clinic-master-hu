# 🔐 Stripe Integration Setup Guide

## Overview
This Laravel application already has Stripe payment integration built-in. You just need to configure it with your Stripe account credentials.

## 📋 Step-by-Step Setup

### Step 1: Create a Stripe Account

1. **Go to Stripe's website**: Visit [https://stripe.com](https://stripe.com)
2. **Click "Start now"** or "Sign up"
3. **Fill in your information**:
   - Email address
   - Full name
   - Country (important for payment processing)
   - Password
4. **Verify your email** by clicking the link Stripe sends you
5. **Complete your business profile** (you can use test mode initially)

### Step 2: Get Your Stripe API Keys

1. **Log in to Stripe Dashboard**: [https://dashboard.stripe.com](https://dashboard.stripe.com)
2. **Navigate to Developers → API Keys**
   - URL: https://dashboard.stripe.com/test/apikeys
3. **You'll see two types of keys**:
   - **Publishable key**: Starts with `pk_test_` (for test mode)
   - **Secret key**: Starts with `sk_test_` (for test mode)
   
   ⚠️ **Important**: Start with TEST keys. Don't use LIVE keys until you're ready for production!

4. **Copy both keys** - you'll need them in the next step

### Step 3: Configure Your Laravel Application

1. **Open your `.env` file** in the project root directory

2. **Add the following Stripe configuration** at the end of the file:

```env
# Stripe Payment Configuration
STRIPE_KEY=pk_test_YOUR_PUBLISHABLE_KEY_HERE
STRIPE_SECRET=sk_test_YOUR_SECRET_KEY_HERE
STRIPE_WEBHOOK_SECRET=
STRIPE_CURRENCY=EGP
```

3. **Replace the placeholders**:
   - Replace `pk_test_YOUR_PUBLISHABLE_KEY_HERE` with your actual Stripe publishable key
   - Replace `sk_test_YOUR_SECRET_KEY_HERE` with your actual Stripe secret key
   - Keep `STRIPE_CURRENCY=EGP` (Egyptian Pound) or change to your currency code (USD, EUR, etc.)

### Step 4: Set Up Stripe Webhooks (Optional but Recommended)

Webhooks allow Stripe to notify your application about payment events.

1. **In Stripe Dashboard**, go to **Developers → Webhooks**
   - URL: https://dashboard.stripe.com/test/webhooks

2. **Click "Add endpoint"**

3. **Enter your webhook URL**:
   ```
   https://your-domain.com/payment/webhook
   ```
   For local development:
   ```
   https://your-ngrok-url.ngrok.io/payment/webhook
   ```

4. **Select events to listen to**:
   - `checkout.session.completed`
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`

5. **Click "Add endpoint"**

6. **Copy the Webhook Secret** (starts with `whsec_`)

7. **Add it to your `.env` file**:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_YOUR_WEBHOOK_SECRET_HERE
   ```

### Step 5: Install Stripe PHP SDK (if not already installed)

The project already includes Stripe in `composer.json`, but verify it's installed:

```bash
composer require stripe/stripe-php
```

Or simply run:
```bash
composer install
```

### Step 6: Test the Integration

1. **Clear Laravel cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Start your application**:
   ```bash
   php artisan serve
   ```

3. **Test with Stripe test cards**:
   - **Success**: `4242 4242 4242 4242`
   - **Decline**: `4000 0000 0000 0002`
   - **3D Secure**: `4000 0025 0000 3155`
   
   Use any future expiry date (e.g., 12/34) and any 3-digit CVC.

## 🔧 Configuration Files

The application uses these Stripe configuration files:

### `config/stripe.php`
```php
return [
    'key' => env('STRIPE_KEY', ''),
    'secret' => env('STRIPE_SECRET', ''),
    'webhook' => [
        'secret' => env('STRIPE_WEBHOOK_SECRET', ''),
        'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
    ],
    'currency' => env('STRIPE_CURRENCY', 'EGP'),
];
```

## 🧪 Testing Webhooks Locally

To test webhooks on your local machine:

1. **Install Stripe CLI**:
   - Download from: https://stripe.com/docs/stripe-cli
   - Or use: `scoop install stripe` (Windows with Scoop)

2. **Login to Stripe**:
   ```bash
   stripe login
   ```

3. **Forward webhooks to your local server**:
   ```bash
   stripe listen --forward-to localhost:8000/payment/webhook
   ```

4. **Copy the webhook signing secret** displayed in terminal to your `.env`:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_xxx
   ```

## 💳 Payment Flow in the Application

1. **Patient books an appointment** with a doctor
2. **System calculates fees** based on doctor's consultation fee
3. **Patient clicks "Pay"** button
4. **Stripe Checkout session** is created
5. **Patient is redirected** to Stripe's secure payment page
6. **Patient enters card details** and completes payment
7. **Stripe processes payment** and redirects back to your app
8. **Payment record** is created in the database
9. **Appointment status** is updated to "confirmed"

## 🎯 Important Routes

The application has these payment routes configured:

- **Checkout page**: `/payments/checkout/{appointment}`
- **Process payment**: `/process-payment/{appointment}`
- **Webhook endpoint**: `/payment/webhook`
- **Success page**: Configured in Stripe checkout session
- **Cancel page**: Configured in Stripe checkout session

## 🛡️ Security Best Practices

1. ✅ **Never commit** your `.env` file to Git
2. ✅ **Use test keys** during development
3. ✅ **Use webhook signatures** to verify webhook authenticity
4. ✅ **Switch to live keys** only when ready for production
5. ✅ **Enable HTTPS** in production (required by Stripe)
6. ✅ **Monitor your Stripe Dashboard** for suspicious activity

## 🔄 Switching to Production

When ready to accept real payments:

1. **Complete Stripe account verification**
2. **Get your LIVE API keys** from Stripe Dashboard
3. **Update `.env` with live keys**:
   ```env
   STRIPE_KEY=pk_live_YOUR_LIVE_KEY
   STRIPE_SECRET=sk_live_YOUR_LIVE_KEY
   ```
4. **Update webhook endpoints** to use production URLs
5. **Test thoroughly** before going live

## 📚 Additional Resources

- **Stripe Documentation**: https://stripe.com/docs
- **Stripe PHP Library**: https://github.com/stripe/stripe-php
- **Test Card Numbers**: https://stripe.com/docs/testing
- **Stripe Dashboard**: https://dashboard.stripe.com

## ❓ Common Issues

### Issue: "No API key provided"
**Solution**: Make sure `STRIPE_SECRET` is set in your `.env` file and run `php artisan config:clear`

### Issue: "Invalid API key"
**Solution**: Verify you copied the correct key from Stripe Dashboard. Keys start with `sk_test_` or `pk_test_`

### Issue: Webhook not receiving events
**Solution**: 
- Verify webhook URL is publicly accessible (use ngrok for local testing)
- Check webhook secret is correctly set
- Verify you selected the correct events in Stripe Dashboard

### Issue: Currency not supported
**Solution**: Check Stripe's supported currencies and update `STRIPE_CURRENCY` in `.env`

## 🎉 You're All Set!

Once you've completed these steps, your Laravel clinic management system will be able to:
- Process appointment payments through Stripe
- Handle secure credit card transactions
- Receive real-time payment notifications via webhooks
- Track payment history in the database

For any issues, check the Laravel logs at `storage/logs/laravel.log`

---
**Last Updated**: January 2026
