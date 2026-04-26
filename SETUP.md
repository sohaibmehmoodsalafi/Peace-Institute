# Peace Institute – Setup Guide

## Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js (optional, for Vite)

## Quick Start

### 1. Install PHP & Composer (Windows)
Download from:
- PHP: https://windows.php.net/download (VS16 x64 Non Thread Safe)
- Composer: https://getcomposer.org/download

Add PHP to your system PATH.

### 2. Install Dependencies
```bash
cd "D:\Peace Institute\Website"
composer install
```

### 3. Environment Setup
```bash
copy .env.example .env
php artisan key:generate
```

### 4. Database
Create a MySQL database:
```sql
CREATE DATABASE peace_institute CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update `.env`:
```
DB_DATABASE=peace_institute
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations & Seed
```bash
php artisan migrate
php artisan db:seed
```

This creates:
- **Admin:** admin@peaceinstitute.com / password
- **Teacher:** yusuf@peaceinstitute.com / password
- **Student:** student@peaceinstitute.com / password

### 6. Storage Link
```bash
php artisan storage:link
```

### 7. Start Server
```bash
php artisan serve
```

Visit: http://localhost:8000

---

## Stripe Setup
1. Create account at https://stripe.com
2. Get your test keys from Dashboard → Developers → API Keys
3. Update `.env`:
```
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

## Zoom Setup (Optional)
1. Create Zoom Marketplace App
2. Choose Server-to-Server OAuth type
3. Add credentials to `.env`

If Zoom is not configured, sessions auto-generate Google Meet-style links.

---

## Application URLs

| Role    | URL                          | Credentials                          |
|---------|------------------------------|--------------------------------------|
| Admin   | /admin/dashboard             | admin@peaceinstitute.com / password  |
| Teacher | /teacher/dashboard           | yusuf@peaceinstitute.com / password  |
| Student | /student/dashboard           | student@peaceinstitute.com / password|
| Public  | /                            | –                                    |

---

## Key Features

### Salary Calculation (EarningsService.php)
```
Earned Amount = (Session Duration in Minutes / 60) × Hourly Rate
Platform Fee  = Earned Amount × 15%
Net Amount    = Earned Amount - Platform Fee
```
- Automatically calculated when teacher marks session as "Complete"
- Stored in `earnings` table with full audit trail
- Teacher sees real-time earnings dashboard

### Booking Flow
1. Student browses teachers → chooses teacher
2. Selects course, duration, date → sees live available slots (AJAX)
3. Confirms booking → system auto-generates meeting link
4. Teacher approves/rejects → student notified
5. Session completed → earnings auto-calculated and recorded

### Role-Based Access
- `admin` middleware → Admin panel (/admin/*)
- `teacher` middleware → Teacher panel (/teacher/*) — checks approval status
- `student` middleware → Student panel (/student/*)

---

## File Structure
```
app/
├── Http/Controllers/
│   ├── Admin/         # Dashboard, Teachers, Students, Bookings, Earnings
│   ├── Auth/          # Login, Register
│   ├── Teacher/       # Dashboard, Availability, Sessions, Earnings
│   └── Student/       # Dashboard, Bookings
├── Models/            # User, Teacher, Student, Course, Booking, ClassSession, Payment, Earning, Review
├── Services/
│   ├── EarningsService.php   # Core salary calculation
│   ├── BookingService.php    # Booking creation, slot logic
│   └── MeetingService.php    # Zoom/Google Meet link generation
└── Http/Middleware/   # RoleMiddleware, AdminMiddleware, TeacherMiddleware, StudentMiddleware

database/migrations/   # 11 migration files
resources/views/       # Blade templates (black/gold Islamic theme)
routes/web.php         # All routes
```
