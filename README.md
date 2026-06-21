# Hostel Management System

A PHP + MySQL web app for managing hostel operations — students can apply
for gate passes, report maintenance issues, request I-Cards, track fee
status, and more. Admins can review and approve/reject all of it from a
dashboard with charts.

This is a rebuilt version of the original project: same features, but with
security fixes, a missing file restored, consistent file paths, and a real
dashboard with visualizations instead of static cards.

---

## 1. Setup (XAMPP)

1. **Copy the project folder** into your XAMPP `htdocs` directory, e.g.:
   ```
   C:\xampp\htdocs\hostel-management-system\
   ```

2. **Start Apache and MySQL** from the XAMPP Control Panel.

3. **Create the database:**
   - Open `http://localhost/phpmyadmin`
   - Click **Import**
   - Choose the file `database/hostel_management.sql` from this project
   - Click **Go**

   This creates the `management` database with all the tables, but with
   **no accounts yet** — passwords must be created through the app so
   they're properly hashed (a hash typed by hand into phpMyAdmin won't
   work as a real password).

4. **Create your first admin login:**
   - Visit `http://localhost/hostel-management-system/setup_first_admin.php`
   - Enter a username and password
   - Submit — this page works exactly once, then locks itself
     automatically once an admin exists (safe to leave in place or
     delete afterwards)

5. **Create student logins:**
   - Log in as admin → **Add / Remove Student** in the sidebar
   - Enter a registration number and password for each student

6. **Check `db.php`** if your MySQL setup uses a different username/password
   than the default XAMPP `root` with no password (most people won't need
   to change this).

7. Open the app in your browser:
   ```
   http://localhost/hostel-management-system/
   ```
   This redirects to the student sign-in page. Admins go to
   `http://localhost/hostel-management-system/admin_login.php`.

---

## 2. Project Structure

```
hostel-management-system/
├── database/
│   └── hostel_management.sql      ← import this first
├── includes/
│   └── functions.php              ← shared auth/CSRF/helper functions
├── css/style.css                  ← all styling
├── js/javascript.js               ← shared form scripts
├── uploads/                       ← gate pass attachments land here
├── db.php                         ← database connection settings
├── signIn.php / admin_login.php   ← entry points
├── dashboard.php / admin_dashboard.php
├── sidebar.php / admin_sidebar.php
└── ...one file per feature (fees, maintenance, leave, refund, etc.)
```

---

## 3. What Was Fixed From the Original

**Security**
- Every page now uses **prepared statements** instead of building SQL by
  hand — the original had SQL injection in `signIn.php`, `index.php`,
  `maintenance.php`, `fees.php`, `leave.php`, `refund.php`, `promotion.php`,
  and several admin pages.
- Student passwords are now stored and checked with `password_hash()` /
  `password_verify()` instead of plain text.
- All values shown back in HTML (descriptions, names, remarks, etc.) are
  now escaped with `htmlspecialchars()` to prevent stored XSS.
- Every form that changes data now includes a **CSRF token** that's
  verified on submit.
- File uploads (gate pass attachments) now validate file type and size
  server-side, not just in JavaScript.
- `hash.php` no longer has a hard-coded real phone number and a working
  hash visible to anyone — it's now an admin-only tool with a form.

**Bugs**
- `logout.php` was referenced everywhere but **did not exist** in the
  original zip — logging out was broken. It's been added.
- File paths were inconsistent (`include "../db.php"` from files that
  live in the same folder as `db.php`) — all paths are now relative to
  the project root consistently.
- `promotion.php` never saved which student submitted the form (no
  `regno` in the INSERT) — fixed, and a submission history table was
  added so students can see their past submissions.
- `update_icard_status.php` required `name`/`year` fields that the
  "Approve" button never actually sent, which would have silently wiped
  the student's I-Card name/year to blank on approval — fixed.
- Two separate, slightly different admin maintenance pages
  (`admin_maintenance.php` and `maintenance_requests.php`) pointed at the
  same update script — consolidated into one.
- Malformed HTML: a `<form>` tag cannot be a direct child of `<tr>` (used
  in the I-Card and Refund admin tables) — browsers silently relocate or
  drop these tags. Rewritten using the HTML5 `form="id"` attribute so the
  table stays valid and the forms work reliably across browsers.
- The student session used `$_SESSION['user_id']` in `index.php`/`nav.php`
  but `$_SESSION['regno']` everywhere else, which meant logging in through
  `index.php` would fail every other page's auth check. Standardized on
  `regno` everywhere.

**Structure**
- Replaced the `<frameset>` (`home.php`) and `<iframe>` (`dashboard.php`)
  page layout with real, separate pages sharing a common sidebar include.
  Frames are deprecated, break the browser back button and bookmarking,
  don't work well on mobile, and are invisible to search engines — normal
  pages avoid all of that while looking the same.

**Visualization**
- The admin dashboard previously just had static link cards with no real
  data. It now shows:
  - Live counts (total students, pending maintenance, pending gate
    passes/leaves, refunds awaiting approval)
  - A 14-day trend line of new maintenance requests
  - A donut chart of maintenance status (Pending vs Solved)
  - A bar chart of maintenance requests by category
  - A donut chart of Gate Pass vs Leave requests

---

## 4. Notes / Things to Know

- The **"Change Information"** workflow stores the student's request as
  free text (e.g. "please update my phone number to..."). Approving it
  marks the request as Approved but does **not** automatically change the
  student's profile — admins still need to manually update the relevant
  field, since the request isn't structured into specific fields. A
  future improvement would be turning that textarea into specific inputs
  (e.g. "New Phone Number") so approval could update the record directly.
- The **maintenance "photo proof"** upload field is currently a visual
  placeholder — wiring it to actual file storage would be a good next
  addition if you need it.
- `setup_first_admin.php` only ever works once (it checks whether any
  admin row already exists) — it's safe to leave in the project, but you
  can delete it after creating your first admin account if you'd rather
  not have it sitting there.
