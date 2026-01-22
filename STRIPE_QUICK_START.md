# 🚀 Quick Start: Stripe Integration

## ⚡ TL;DR - Get Started in 5 Minutes

### 1️⃣ Create Stripe Account
👉 Go to: **https://stripe.com** → Click "Sign up"

### 2️⃣ Get Your API Keys
👉 Go to: **https://dashboard.stripe.com/test/apikeys**

You'll see:
- **Publishable key**: `pk_test_xxxxxxxxxxxxx`
- **Secret key**: `sk_test_xxxxxxxxxxxxx` (click "Reveal" to see it)

### 3️⃣ Add Keys to Your .env File

Open the file: `.env` in your project root and update these lines (around line 76-77):

```env
STRIPE_KEY=pk_test_YOUR_KEY_HERE
STRIPE_SECRET=sk_test_YOUR_KEY_HERE
STRIPE_WEBHOOK_SECRET=
STRIPE_CURRENCY=EGP
```

### 4️⃣ Clear Cache & Test

Run these commands:
```bash
php artisan config:clear
php artisan serve
```

### 5️⃣ Test Payment

Use Stripe's test card:
- **Card Number**: `4242 4242 4242 4242`
- **Expiry**: Any future date (e.g., `12/34`)
- **CVC**: Any 3 digits (e.g., `123`)
- **ZIP**: Any 5 digits (e.g., `12345`)

---

## 📝 Example .env Configuration

```env
# ==========================================
# Stripe Payment Gateway
# ==========================================
STRIPE_KEY=pk_test_51AbCdEf...
STRIPE_SECRET=sk_test_51AbCdEf...
STRIPE_WEBHOOK_SECRET=
STRIPE_CURRENCY=EGP
```

---

## 🔗 Important Links

| What | Link |
|------|------|
| Create Account | https://stripe.com |
| Get API Keys | https://dashboard.stripe.com/test/apikeys |
| Test Cards | https://stripe.com/docs/testing |
| Full Documentation | See `STRIPE_SETUP_GUIDE.md` |

---

## ⚠️ Important Notes

- ✅ Use **TEST keys** (start with `pk_test_` and `sk_test_`)
- ✅ Never commit your `.env` file to Git
- ✅ The code is already integrated - you just need to add keys!
- ✅ For production, you'll need to activate your Stripe account and use LIVE keys

---

## 🆘 Need Help?

Check the full guide: `STRIPE_SETUP_GUIDE.md`
