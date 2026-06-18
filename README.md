# 🏨 MyRoom v2 — Complete Hotel Booking Platform

> **Design refresh (2025):** The entire UI has been rebuilt on a new premium design system — a "time-aware" identity for hourly stays. Midnight-indigo + iris + signal-amber palette, Space Grotesk / Inter / JetBrains Mono type, an inline SVG icon set (no emoji), scroll-reveal motion, and a fully responsive, accessible layout. SEO and social-share metadata (Open Graph, Twitter cards, JSON-LD, robots.txt, default OG image) are wired throughout. Functionality, routes, and data contracts are unchanged.


> Multi-role · Booking workflow with hotel accept/reject · Real-time notifications · No Node.js / No Vite

---

## Quick Start (3 commands)

```bash
composer install
cp .env.example .env && php artisan key:generate
mysql -u root -e "CREATE DATABASE myroom_v2 CHARACTER SET utf8mb4;"
php artisan migrate --seed
php artisan serve
```

Visit **http://localhost:8000**

---

## What's New in v2

- ✅ Multi-role auth: Customer · Hotel Owner · Admin
- ✅ **Booking workflow**: Hotel accepts/rejects → customer notified via SMS
- ✅ **Customer dashboard**: booking history, notifications, wishlist
- ✅ **Hotel Extranet** (Bag2Bag-style): manage hotels, rooms, availability
- ✅ **Notification system**: in-app + SMS (Fast2SMS)
- ✅ **Reviews & ratings**: customers can review after completed stays
- ✅ **Room availability calendar**: hotel blocks dates
- ✅ Partial or full payment choice
- ✅ Wishlist / Saved hotels
- ✅ Booking PDF download
- ✅ Google OAuth + Mobile OTP login
- ✅ Offers/promo codes with validation
- ✅ Admin: approve hotels, monitor bookings, manage settings
- ✅ SEO: Schema.org JSON-LD, sitemaps, OG tags
- ✅ No npm · No Vite · CDN-only (Tailwind + Alpine.js)

---

## Tech Stack

| Layer | Details |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade, Alpine.js CDN, Tailwind CSS CDN |
| Database | MySQL 8+ |
| Auth | Mobile OTP (Fast2SMS) + Email/Password + Google OAuth |
| Payments | Razorpay + PhonePe |
| PDF | DomPDF |
| SMS | Fast2SMS |

---

## Credentials

See `ADMIN_CREDENTIALS.md`

---

## Key Pages

| URL | Description |
|---|---|
| / | Home (Brevistay-style hero + search) |
| /search | Hotel listing with area/price/type filters |
| /hotels-in/{city} | City-specific SEO page |
| /hotel/{slug} | Hotel detail |
| /book/{hotel}/{room} | Booking form (no login needed) |
| /track-booking | Track by ID + phone |
| /login | OTP / Email / Google |
| /register | Customer registration |
| /list-hotel | Hotel owner registration |
| /customer/dashboard | Customer panel |
| /hotel/dashboard | Hotel extranet |
| /hotel/bookings | Accept/reject bookings |
| /admin/dashboard | Admin panel |
| /sitemap.xml | SEO sitemap |

---

## ⚠️ Troubleshooting

### "View [vendor.pagination.tailwind] not found"
Already fixed — pagination is registered as the default view in `AppServiceProvider`. If you still see it after pulling old code, clear caches:
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### "Undefined variable $seo"
Fixed — `AppServiceProvider::boot()` shares a default `$seo` with every view via `View::share()`. Make sure `bootstrap/providers.php` lists `App\Providers\AppServiceProvider::class`.

### Blade "unexpected end of file" / "unexpected endforeach"
Caused by Blade directive keywords (`@php`, `@if`, etc.) written **inside** `{{-- comments --}}`. Blade's compiler counts them. Never put `@directive` text inside a Blade comment.

### After any change, always run:
```bash
php artisan view:clear && php artisan optimize:clear
```

---

## 💳 Payment Setup (IMPORTANT)

### "Payment Failed — Authentication key was missing during initialization"
This means Razorpay keys aren't set. **The app now handles this automatically:**

**Demo/Test Mode (default):** Leave `RAZORPAY_KEY_ID` and `RAZORPAY_KEY_SECRET` empty in `.env`. The payment page shows a "Simulate Payment & Confirm" button so you can test the full booking → confirmation → hotel-accept flow without real keys.

**Live Mode:** Add real keys to `.env`:
```env
RAZORPAY_KEY_ID=rzp_test_XXXXXXXXXXXXXX
RAZORPAY_KEY_SECRET=your_secret_here
```
Get free test keys at https://dashboard.razorpay.com/app/keys → the real Razorpay checkout activates automatically.

After editing `.env`, always run:
```bash
php artisan config:clear
```
