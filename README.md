# HostelHub — Smart Hostel Management System

A web-based hostel management system built with **Laravel 12**, combining a public marketing
website with a secure administrator panel, backed by a MySQL-compatible relational database.

## What's included in this package

This package contains the **application layer** (the parts specific to HostelHub) that is
meant to sit on top of a standard Laravel 12 installation:

- `app/Models` — Admin, Room, Resident, RoomAllocation, Service, GalleryImage, ContactMessage
- `app/Http/Controllers/Admin` — full CRUD controllers for the admin panel
- `app/Http/Controllers/Site` — public website controllers
- `app/Http/Requests` — server-side validation (FormRequests) for every write operation
- `app/Http/Middleware/EnsureAdminIsActive.php` — extra guard for disabled admin accounts
- `database/migrations` — 8 tables: admins, rooms, residents, room_allocations, services, gallery, contacts, payments
- `database/seeders` — creates a default admin account
- `resources/views` — Blade views for both the public site and the admin panel (Bootstrap 5)
- `routes/web.php` — all public + admin routes
- `config/auth.php` — a dedicated `admin` guard (separate from a future public "users" guard)
- `bootstrap/app.php` — Laravel 11/12 style middleware registration
- `.env.example`, `composer.json`

It does **not** include the Laravel framework's own generated skeleton files (`public/index.php`,
`artisan`, base `config/*.php` files other than `auth.php`, `vendor/`, etc.) — those are produced
by Laravel's installer itself, not hand-written, and pulling framework packages requires
`composer create-project`, which needs registry access this environment doesn't have.

## Setup Instructions

1. **Create a fresh Laravel 12 app** (on your machine, with internet access):
   ```bash
   composer create-project laravel/laravel hostelhub
   cd hostelhub
   ```
   > **Why Laravel 12 and not 11?** As of Composer 2.9+, `composer install` refuses to
   > resolve any package version with an open security advisory. Laravel 11 is past its
   > security-support window, so every 11.x release is now blocked by default. This
   > package's `composer.json` targets `laravel/framework: ^12.0` for that reason — no
   > code in this package (models, controllers, views, migrations, `bootstrap/app.php`,
   > `config/auth.php`) needs to change to run on 12, since Laravel 12 kept the same
   > `Application::configure()` bootstrap style introduced in 11.
   >
   > If you're stuck on 11.x for another reason, you can bypass the block per-advisory
   > instead of upgrading, by adding to your `composer.json`:
   > ```json
   > "config": {
   >     "policy": { "advisories": { "block": false } }
   > }
   > ```
   > This is a stopgap, not a fix — those releases are unsupported, so plan to move to 12.

2. **Copy the contents of this package into it**, overwriting where noted:
   - Copy everything under `app/`, `database/migrations/`, `database/seeders/`, `resources/views/`
   - Replace `routes/web.php`
   - Replace `bootstrap/app.php`
   - Replace `config/auth.php`
   - Merge `.env.example` values into your `.env`

3. **Configure your database** in `.env` (MySQL):
   ```
   DB_DATABASE=hostelhub
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Install dependencies & generate app key:**
   ```bash
   composer install
   php artisan key:generate
   ```

5. **Run migrations and seed the default admin:**
   ```bash
   php artisan migrate --seed
   ```
   Default login (⚠️ **change immediately**):
   - Email: `admin@hostelhub.test`
   - Password: `ChangeMe123!`

6. **Link storage** (for gallery/service image uploads):
   ```bash
   php artisan storage:link
   ```

7. **Serve the app:**
   ```bash
   php artisan serve
   ```
   - Public site: `http://localhost:8000`
   - Admin login: `http://localhost:8000/admin/login`

## Architecture Notes

- **Two guards, one app:** `admin` guard (session-based, `admins` table) protects everything
  under `/admin`. There's no public "user" login yet — that's a planned Resident Portal.
- **Room occupancy is derived, not manually edited.** `Room::syncOccupancy()` recalculates
  `current_occupancy` and `status` from active `room_allocations` every time an allocation is
  created, ended, or deleted — this prevents room/resident data from drifting out of sync,
  which was one of the core problems the prospectus identifies.
- **Validation lives in FormRequests**, not controllers, per Laravel convention — keeps
  controllers thin and makes rules reusable/testable.
- **File uploads** (`services.image`, `gallery.image`) are validated for MIME type and size,
  stored on the `public` disk, and old files are deleted on replace/delete to avoid orphaned files.
- **CSRF** protection is Laravel's default (`@csrf` in every form). **Password hashing** uses
  Laravel's `hashed` cast (bcrypt). **Mass assignment** is locked down via `$fillable` on every model.

## Suggested next steps for the assessment deliverables

- `php artisan config:cache` / production checklist before demoing
- Export your seeded/sample-populated database via `mysqldump` for the `.sql` deliverable
- Add PHPUnit/Pest feature tests for auth, each CRUD module, and the contact form
- Take screenshots per the prospectus's Section 33 checklist once seeded with sample data

## Changelog

**Bug fixes**
- Resident search combined with a status filter now correctly narrows by both — the previous `orWhere` chain broke Laravel's implicit AND/OR grouping, silently ignoring the status filter for two of the three searched columns.
- The service enable/disable checkbox now actually saves "disabled" — an unchecked checkbox sends no form field at all, so the old code always fell back to `true`.
- Deleting a room or resident with any allocation/payment history (active or past) is now blocked with a friendly message instead of silently cascading and destroying the audit trail.
- Ending or deleting a room allocation now checks its current status first, preventing double-ending (which overwrote checkout dates) and preventing deletion of still-active allocations.
- CNIC-format validation is now conditional on an explicit "ID Type" field (CNIC / Passport / Other), so foreign or passport-holding residents aren't blocked by a Pakistani-CNIC-only regex.
- `/admin/login` is now rate-limited (5 attempts/minute) to reduce brute-force exposure.

**New features**
- **Payments module** — record rent payments per resident (amount, month covered, method, reference), with a dashboard/list view showing totals collected this month and all-time, and per-resident payment history on the resident detail page.
- **Admin profile page** — admins can now update their own name/email and change their password (there was previously no way to do this after the seeded default account).
- **CSV export** for the residents list, respecting whatever search/status filters are currently applied.

## Running the Tests

This package includes a PHPUnit feature test suite (`tests/Feature/Admin/`) that specifically
targets every bug fixed in the changelog above, so a regression gets caught automatically
instead of silently coming back. After merging this package into a fresh Laravel install:

```bash
composer install
cp .env.example .env   # if you haven't already
php artisan test
```

Tests run against an in-memory SQLite database (configured in `phpunit.xml`) — no MySQL
setup is needed to run them, and they never touch your real `.env` database.

**What's covered, file by file:**

| Test file | What it guards against |
|---|---|
| `ResidentSearchFilterTest` | The `orWhere`/AND-precedence bug — search + status filter combining incorrectly |
| `ServiceStatusToggleTest` | The checkbox bug — unchecking "enabled" silently saving as enabled anyway |
| `RoomDeletionGuardTest` | Deleting a room with active **or historical** allocations wiping audit history |
| `ResidentDeletionGuardTest` | Deleting a resident with allocation or payment history |
| `RoomAllocationGuardTest` | Double-ending an allocation (overwriting checkout date); deleting a still-active allocation; occupancy/status staying in sync through the allocation lifecycle |
| `RoomAllocationDuplicateGuardTest` | Allocating a resident who's already housed elsewhere without explicit confirmation; allocating into a full room |
| `ResidentIdentificationValidationTest` | CNIC format being wrongly enforced on passport/other ID types; CNIC normalization (dashes) |
| `LoginThrottleTest` | Disabled admin accounts logging in; missing rate limiting on `/admin/login` |

Each test's docblock explains the exact original bug it would catch if reintroduced.

**Factories:** `database/factories/` includes factories for every model used in tests
(`Admin`, `Room`, `Resident`, `RoomAllocation`, `Service`, `Payment`), generating data that
already satisfies the app's own validation rules (valid CNIC shape, valid phone format, etc.)
so tests aren't fighting the same validation they're meant to exercise.
