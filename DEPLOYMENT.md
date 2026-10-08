# CailaVerse — Deployment Guide

> This guide explains how to deploy CailaVerse on a real PHP + MySQL web server.
> GitHub Pages **cannot** host this application — it requires server-side PHP and MySQL.

---

## Requirements

| Requirement | Minimum Version |
|---|---|
| PHP | **8.1** or higher (8.2 recommended) |
| MySQL / MariaDB | **5.7 / 10.3** or higher |
| Apache | With **mod_rewrite** enabled |
| PHP Extensions | `pdo`, `pdo_mysql`, `fileinfo`, `session` |

---

## Recommended Hosting Providers

- **Hostinger** (cheapest paid, document root configurable) ✅ Recommended
- **Namecheap Shared Hosting** ✅
- **InfinityFree** (free tier, limited) ⚠️
- **000webhost** (free tier, limited) ⚠️
- Any shared hosting with **cPanel** that runs Apache + PHP 8.x

> **Do NOT use:** GitHub Pages, Netlify, Vercel, Cloudflare Pages — these cannot run PHP.

---

## Step 1 — Set Up Your MySQL Database

### On cPanel Hosting:
1. Log in to your cPanel → **MySQL Databases**
2. Create a new database (e.g., `yourusername_social_app`)
3. Create a new MySQL user with a strong password
4. **Add user to database** with **All Privileges**
5. Go to **phpMyAdmin** → select your new database
6. Click **Import** → choose the file `sql/social_app.sql` → click **Go**

### On a VPS / Root Server:
```bash
mysql -u root -p
CREATE DATABASE social_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'caila_user'@'localhost' IDENTIFIED BY 'YourStrongPassword123!';
GRANT ALL PRIVILEGES ON social_app.* TO 'caila_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

mysql -u caila_user -p social_app < sql/social_app.sql
```

---

## Step 2 — Upload Project Files

Upload the **entire project** to your server. The folder structure must be:

```
your_upload/
├── app/
├── config/
├── public/         ← This must be the web document root
│   ├── index.php
│   ├── assets/
│   └── uploads/
├── sql/
├── .htaccess
├── README.md
└── DEPLOYMENT.md
```

### Option A — Document Root = `public/` (RECOMMENDED)

If your hosting lets you set the **document root** to the `public/` folder:
- Upload the entire project to a folder on your server (e.g., `/home/yourusername/cailaverse/`)
- In cPanel → **Domains** or **Addon Domains** → set document root to:
  ```
  /home/yourusername/cailaverse/public
  ```
- Your site will work at `https://yourdomain.com/`

### Option B — Document Root = Project Root (e.g., `public_html/`)

If your host forces `public_html/` as the document root:
- Upload everything directly into `public_html/`
- The root `.htaccess` file will redirect web traffic into `public_html/public/`
- Your site will work at `https://yourdomain.com/`

> **Note:** Option A is always preferred. The root `.htaccess` in Option B works but is less clean.

---

## Step 3 — Configure Database Credentials

Edit the file `config/database.php`. Find these constants:

```php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'social_app');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
```

**Option A — Edit directly** (simplest for shared hosting):
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'yourusername_social_app');   // your cPanel DB name
define('DB_USER', 'yourusername_dbuser');        // your cPanel DB username
define('DB_PASS', 'YourStrongPassword123!');     // your DB password
```

**Option B — Environment Variables** (safer, credentials stay out of code):
In cPanel → **Software → PHP Configuration** or your `.htaccess`:
```
SetEnv DB_HOST localhost
SetEnv DB_NAME yourusername_social_app
SetEnv DB_USER yourusername_dbuser
SetEnv DB_PASS YourStrongPassword123!
```

---

## Step 4 — Set Upload Directory Permissions

The `public/uploads/` directory must be **writable** by the web server.

```bash
chmod 755 public/uploads/
```

On cPanel, right-click `public/uploads/` in **File Manager** → **Permissions** → set to `755`.

> The `public/uploads/.htaccess` already blocks PHP execution inside that folder for security.

---

## Step 5 — Enable mod_rewrite (Apache)

Make sure `mod_rewrite` is enabled. On a VPS:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

On shared hosting (cPanel), `mod_rewrite` is enabled by default.

In your Apache `VirtualHost` config, ensure:
```apache
<Directory /path/to/cailaverse/public>
    AllowOverride All
</Directory>
```

---

## Step 6 — Verify PHP Settings

Recommended `php.ini` values (set via cPanel → PHP Configuration or `.htaccess`):
```ini
upload_max_filesize = 5M
post_max_size = 8M
max_execution_time = 60
session.gc_maxlifetime = 3600
```

In `.htaccess` format:
```apache
php_value upload_max_filesize 5M
php_value post_max_size 8M
php_value max_execution_time 60
php_value session.gc_maxlifetime 3600
```

---

## URL Structure After Deployment

| Page | URL |
|---|---|
| Login | `https://yourdomain.com/index.php?c=auth&a=login` |
| Register | `https://yourdomain.com/index.php?c=auth&a=register` |
| News Feed | `https://yourdomain.com/index.php?c=post&a=feed` |
| Profile | `https://yourdomain.com/index.php?c=profile&a=show&id=1` |
| Edit Profile | `https://yourdomain.com/index.php?c=profile&a=edit` |
| Search | `https://yourdomain.com/index.php?c=search&a=index&q=keyword` |

> With the `.htaccess` rewrite rule in `public/`, visiting `https://yourdomain.com/` will also work and redirect to the feed.

---

## Security Checklist

- [x] PDO prepared statements (SQL injection protected)
- [x] `password_hash` / `password_verify` (passwords never stored plain)
- [x] CSRF token on all forms
- [x] `htmlspecialchars()` escaping on all output
- [x] PHP execution blocked inside `public/uploads/` via `.htaccess`
- [x] Directory listing disabled
- [ ] **TODO:** Move `config/database.php` credentials to environment variables
- [ ] **TODO:** Set PHP `display_errors = Off` in production
- [ ] **TODO:** Use HTTPS (get a free Let's Encrypt SSL from your cPanel)

---

## Troubleshooting

| Problem | Fix |
|---|---|
| White screen / 500 error | Check PHP error log. Enable `display_errors` temporarily |
| "Page not found" on all URLs | `mod_rewrite` not enabled or `AllowOverride All` missing |
| Database connection failed | Double-check `config/database.php` credentials |
| Images not showing | Check `public/uploads/` permissions (should be 755) |
| AJAX reactions not working | Make sure `public/` is the document root (relative paths depend on it) |
| Session not persisting | Ensure `session.save_path` is writable on the server |

---

## Local Development (XAMPP)

The project is already configured for local XAMPP with:
- **DB Host:** `localhost`
- **DB Name:** `social_app`
- **DB User:** `root`
- **DB Pass:** *(empty)*

Access locally at:
```
http://localhost/cailafolder/social_app/public/index.php
```

---

*CailaVerse — Developed by John Michael Caila, BSCS, Saint Michael College of Caraga (SMCC)*
