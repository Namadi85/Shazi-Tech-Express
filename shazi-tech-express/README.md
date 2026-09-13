# Shazi Tech Express V3 — VTU Platform MVP

PHP 8.1+ / MySQL application for a Nigerian VTU/reseller business.

## Included
- Registration/login with hashed passwords and CSRF protection
- User wallet + Paystack funding
- Paystack callback verification and signed webhook handling
- Data catalogue sync from VTU.ng API v2
- Admin-controlled data selling prices and activation
- Data purchase with wallet locking/debit and automatic reversal on provider failure
- Airtime purchase
- Electricity and cable TV service purchase adapter
- Referral codes and referral tracking
- User notifications
- Admin dashboard, users/roles and plan management
- Shazi Tech Express logo and responsive interface

## Important provider note
VTU.ng documents API v2 endpoints for data, airtime, electricity, TV and customer verification. The exact credentials, account permissions, IP whitelist and production status depend on your VTU.ng account. Do not put secret keys in browser code.

Paystack recommends server-side transaction verification and signed webhooks before delivering wallet value. This project keeps the secret key on the server and checks webhook signatures.

## Setup
1. Requirements: PHP 8.1+, MySQL 8/MariaDB, PDO MySQL and cURL.
2. Import `sql/schema.sql` into MySQL.
3. Copy `.env.example` to your hosting environment and set real credentials.
4. Point your web server document root to `public/`.
5. Create an account and promote it to admin:
   `UPDATE users SET role='admin' WHERE email='your-email@example.com';`
6. Login as admin and sync data plans.
7. Review sell prices before enabling plans for customers.
8. In Paystack dashboard configure your webhook URL as:
   `https://YOUR-DOMAIN/webhook-paystack.php`

## Production checklist
- HTTPS
- Strong database password
- Test Paystack first, then switch to live keys
- Keep API secrets outside the public directory
- Configure backups and error logging
- Add provider requery/cron for orders that remain processing
- Test all wallet debit/refund paths before launch
