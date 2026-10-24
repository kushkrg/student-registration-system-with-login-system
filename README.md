# Student Registration System with Login System

A secure, beautiful **student registration & management system** built with **Core PHP + MySQL** (no framework). Register students, manage their complete records, search them, view a rich profile page, and export data to CSV — all behind a secure login.

## Features

- **Secure Login & Registration** — `password_hash()` / `password_verify()`, prepared statements, CSRF token on every form, session regeneration after login, one generic login error (never reveals if a username exists)
- **Student Management** — Add / View / Edit / Delete with a complete registration form (personal, family, contact & address details + photo)
- **Beautiful Student Profile Page** — gradient profile header with photo, age badge, grouped information cards, and a Print button (print-friendly)
- **Live Search & Pagination** — search by name, student ID, phone or email
- **CSV Export** — one click, exports exactly what your search shows, as a real `.csv` file
- **Photo Upload, Done Safely** — JPG/PNG/WEBP only, 2 MB limit, random file names, and the upload folder blocks PHP execution
- **Modern Responsive UI** — Warm Paper & Deep Teal design, sidebar dashboard, stat cards, works on mobile
- **Dashboard Stats** — total students, male/female counts

## Setup (2 minutes)

1. Copy this project into `htdocs` (XAMPP) / `www` (WAMP) / `public_html` (hosting).
2. Open **phpMyAdmin → Import** and import `Database/admission.sql` (creates the `admission` database and both tables — no demo/personal data included).
3. If your MySQL setup is not `root` with an empty password, update `config.php`.
4. Open `http://localhost/student-registration-system-with-login-system/register.php`, create your account, then login.

> **Upgrading from the old version?** Your existing `admission` database keeps working — database, table and column names are unchanged. The new `Database/admission.sql` is only needed for fresh installs.

## Project Structure

```
config.php                  Database connection (mysqli, utf8mb4)
includes/functions.php      Session, CSRF, flash messages, auth guard, secure upload, validation
includes/student-form.php   Shared Add / Edit student form
header.php / footer.php     Dashboard layout (sidebar, topbar)
index.php                   Students list + search + pagination + stats
add.php                     Add student (login required)
edit.php                    Edit student (login required, photo is preserved)
view.php                    Beautiful student profile page (+ print)
delete.php                  Delete student (POST + CSRF only)
export.php                  CSV export (login required, follows search)
login.php / register.php / logout.php / welcome.php / reset-password.php
assets/css/style.css        Complete modern stylesheet (no Bootstrap/jQuery needed)
assets/js/app.js            Show/hide password, confirm dialogs, alerts
upload_images/              Student photos (PHP execution blocked by .htaccess)
images/                     Logo
Database/admission.sql      Clean database schema
```

## Security fixes in this update (2026)

This project was originally written in 2021 as a learning project. This update fixes the serious issues found in the old code — **please upgrade**:

| # | Issue in old code | Fix |
|---|---|---|
| 1 | `add.php`, `edit.php`, `delete.php`, `export.php` had **no login check** — anyone could add/edit/delete/export every student (including Aadhar numbers) | All pages now require a logged-in session |
| 2 | **SQL injection everywhere** — `$_POST`/`$_GET` values pasted directly into SQL strings (`DELETE ... WHERE id = $id`) | Every query is a prepared statement |
| 3 | **Stored XSS** — student data printed into pages without escaping | All output escaped with `htmlspecialchars` (`e()` helper) |
| 4 | **Unrestricted file upload** — any file type with its original name in a public folder (PHP shell = server takeover) | Image types only, 2 MB, random names, `upload_images/.htaccess` blocks PHP |
| 5 | **Delete worked via a GET link** with no CSRF protection | Delete is POST-only with a CSRF token + confirmation |
| 6 | **Export was broken** — index linked with GET but export waited for POST; it also sent an HTML table named `.xls` | Real CSV via GET, follows the current search, login required |
| 7 | **Edit wiped the photo** when no new photo was chosen | Old photo is preserved on edit |
| 8 | `welcome.php` used **broken images** (`images/user.png`, a GIF) that don't exist in the repo | Fixed to the local logo/avatar, welcome is now a proper account page |
| 9 | **Login revealed** whether a username exists ("No account found" vs "wrong password"), and the session ID was never regenerated | One generic error + `session_regenerate_id()` after login |
| 10 | SQL dump shipped **real personal data** (names, emails, Aadhar numbers) and a broken schema (unused `file` longblob NOT NULL broke inserts on strict MySQL; `u_state` varchar(12) truncated state names) | Clean schema-only dump, fixed column types, no personal data |
| 11 | Connection errors leaked `mysqli_connect_error()` to visitors | Generic error message, no server details |
| 12 | No input validation | Server-side validation: email format, 12-digit Aadhar, 6-digit pincode, phone, required fields |

The UI was also rebuilt: the old version mixed Bootstrap 3 + 4 + DataTables CDNs with hotlinked external images; the new UI is one self-contained stylesheet with a modern sidebar dashboard.

## Requirements

- PHP 8.0+ with `mysqli` extension
- MySQL 5.7+ / MariaDB 10.3+
- Apache (XAMPP / WAMP / LAMP / shared hosting)

## Author

**Lavkush Kumar** — [Coding Cush](https://codingcush.com) · [YouTube](https://www.youtube.com/@CodingCush)

## License

MIT — see [LICENSE](LICENSE).
