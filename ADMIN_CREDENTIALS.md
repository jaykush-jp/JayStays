# 🔐 Admin & Credentials Guide — MyRoom v2

## Default Credentials (from Seeder)

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@myroom.in | Admin@123 |
| **Hotel 1** | hotel.admire@myroom.in | Hotel@123 |
| **Hotel 2** | spectrum@myroom.in | Hotel@123 |
| **Customer** | demo@myroom.in | Demo@123 |

---

## Panel URLs

| Panel | URL |
|---|---|
| Admin Panel | `/admin/dashboard` |
| Hotel Extranet | `/hotel/dashboard` |
| Customer Dashboard | `/customer/dashboard` |
| Login | `/login` |

---

## Where Are Admin Credentials Stored?

`database/seeders/DatabaseSeeder.php` — `run()` method:

```php
User::updateOrCreate(['email' => 'admin@myroom.in'], [
    'name'     => 'MyRoom Admin',
    'password' => Hash::make('Admin@123'),
    'role'     => 'admin',
    'status'   => 'active',
]);
```

---

## How to Create/Reset Admin

### Via artisan tinker:
```bash
php artisan tinker
User::updateOrCreate(['email'=>'admin@myroom.in'],['name'=>'Admin','password'=>bcrypt('NewPassword'),'role'=>'admin','status'=>'active']);
exit;
```

### Re-run seeder:
```bash
php artisan db:seed
```

---

## How to Create Hotel Owner Accounts

**Option 1:** Via Admin Panel → Users → Create Hotel Owner

**Option 2:** Hotel owner self-registers at `/list-hotel`
- After registration, hotel is in **pending** status
- Admin approves at `/admin/hotels`
- Hotel owner then accesses the extranet

**Option 3:** Via artisan tinker:
```bash
php artisan tinker
User::create(['name'=>'Owner Name','email'=>'owner@hotel.com','phone'=>'9876543210','password'=>bcrypt('pass'),'role'=>'hotel_owner','status'=>'active']);
exit;
```

---

## Booking Workflow Summary

```
Customer books → pays advance online
    ↓
Booking status: PENDING
    ↓
Hotel owner gets SMS + in-app notification
    ↓
Hotel logs in → /hotel/bookings
    ↓
ACCEPT (status→confirmed, customer SMS)
OR
REJECT (status→rejected, refund initiated, customer SMS)
    ↓
Customer arrives → Hotel marks Checked In
    ↓
Stay complete → Hotel marks Completed
    ↓
Customer can write review
```

---

## Common Auth Issues

| Issue | Fix |
|---|---|
| "Credentials don't match" | Run `php artisan db:seed` |
| "SQLSTATE: Table doesn't exist" | Run `php artisan migrate` first |
| Admin redirected to home | Check `role = 'admin'` and `status = 'active'` in users table |
| Hotel owner can't access panel | Check `status = 'active'`; hotel must also be approved |
