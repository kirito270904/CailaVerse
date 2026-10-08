# CailaVerse — Mini Social Networking Web Application

**Web Systems and Technologies — Final Output**  
**Saint Michael College of Caraga (SMCC)**  
**Developer:** John Michael Caila  
**Architecture:** Full-Stack PHP MVC + MySQL + Modern Responsive UI  

---

## 🚀 How to Run (XAMPP Setup)

1. Make sure your project folder is located at:
   ```
   C:\xampp\htdocs\cailafolder\social_app\
   ```
2. Open the **XAMPP Control Panel** and start **Apache** and **MySQL**.
3. Open your browser and go to **phpMyAdmin**: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
4. Click **Import**, choose the file [`sql/social_app.sql`](sql/social_app.sql), and click **Go**.
   *(This initializes the database with all 5 relational tables, sample users, posts, comments, reactions, and friend connections).*
5. Open the web app in your browser:
   ```
   http://localhost/cailafolder/social_app/public/index.php
   ```
   *(Or if placed directly under htdocs: `http://localhost/social_app/public/index.php`)*
6. **Demo Accounts** (Password for all accounts is: `password`):
   - **`john`** (John Michael Caila — Founder & Developer)
   - **`juan`** (Juan Dela Cruz)
   - **`maria`** (Maria Santos)
   - **`pedro`** (Pedro Reyes)

> [!NOTE]  
> If your MySQL root user has a password, update credentials in [`config/database.php`](config/database.php). An active internet connection is recommended for CDN assets (Bootstrap 5.3.3 & Bootstrap Icons).

---

## 🌟 Implemented Features

### 1. Core Modules (Based on Project Specification)
- **User Authentication Module:**
  - Registration with both client-side and server-side validation.
  - Secure Login & Logout with session regeneration (`session_regenerate_id()`).
  - Strong password hashing with `password_hash()` and `password_verify()`.
  - Profile photo upload with file type/size validation and auto-fallback.
- **User Profile:**
  - Dynamic user profile page with custom cover banner, avatar, bio, and member Node ID.
  - Profile statistics: Posts count, Friends count, and Join date.
  - Profile editing: Update name, bio, and profile photo.
- **Posts (CRUD Module #1):**
  - **Create:** Text posts with optional photo upload.
  - **Read:** Chronological timeline (latest first) with author details and timestamps.
  - **Update:** Edit post content and update/remove images.
  - **Delete:** Delete own posts with confirmation modal.
- **Comments (CRUD Module #2):**
  - **Create:** Comment on any post with instant AJAX insertion.
  - **Update:** Edit existing comments.
  - **Delete:** Remove own comments.
- **Search & User Discovery:**
  - Search users by username or full name.
  - Search posts by keyword.
  - One-click "Add Friend" button directly from search results.

---

### 2. Facebook-Style Interactive Features
- **Facebook Reactions Dock:**
  - Hover or tap the Like button to reveal the floating reaction dock:
    - 👍 **Like**
    - ❤️ **Love**
    - 🥰 **Care**
    - 😆 **Haha**
    - 😮 **Wow**
    - 😢 **Sad**
    - 😡 **Angry**
  - Dynamic reaction counter with active badge styling and toggle functionality.
- **Facebook-Style Post Sharing:**
  - Click **Share** on any post to open the Share Modal.
  - Add your own thoughts and click **Share Now** to publish it to your timeline.
  - Embedded preview of the original post (author, avatar, timestamp, text, and media).
  - One-click "Copy Link" to share outside the network.
- **Add Friend / Follow System:**
  - Click **Add Friend** / **Friends** to connect with users.
  - Live friend counters updated in real-time.
  - Quick-connect actions available in **Discover People**, search results, and profiles.

---

### 3. Bonus Advanced Features (+10 Points from Rubric)
- ⚡ **AJAX-Based Operations:** Instant posting, commenting, reacting, sharing, following, and deleting without page reloads.
- 🌓 **Dark Mode Toggle:** Smooth animated light/dark mode switch with `localStorage` persistence.
- 🛡️ **Strict File & Image Validation:** Checks both MIME type (`finfo`) and file extension (`jpg, png, gif, webp`), with a 2MB maximum limit.
- 🎨 **High-Tech Cyber-Networking Design ("Pang Networking"):** Ambient glow, glassmorphic cards, live online status indicators (`🟢 Online`), and responsive 3-column layout.

---

## 🔒 Security Practices
1. **SQL Injection Prevention:** 100% prepared statements using PDO throughout all models.
2. **XSS Guard:** All dynamic outputs escaped using `htmlspecialchars()` via the `e()` helper function.
3. **CSRF Protection:** Cryptographic CSRF tokens generated per session and validated on every `POST` request.
4. **Authorization Checks:** Server-side ownership validation before allowing any edit or delete operations.

---

## 📂 Project Architecture (MVC Structure)

```
social_app/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php      # Login, registration, and logout
│   │   ├── CommentController.php   # Comment CRUD business logic
│   │   ├── Controller.php          # Base controller (views, sessions, CSRF, auth)
│   │   ├── FollowController.php    # Add Friend / Follow toggle logic
│   │   ├── PostController.php      # Post CRUD, reactions, and sharing logic
│   │   ├── ProfileController.php   # User profile viewing and editing
│   │   └── SearchController.php    # User and post search queries
│   ├── models/
│   │   ├── CommentModel.php        # Comments database queries
│   │   ├── FollowModel.php         # Follow/friend relationship queries
│   │   ├── LikeModel.php           # Facebook reaction logic and stats
│   │   ├── PostModel.php           # Post CRUD, feed, and sharing queries
│   │   └── UserModel.php           # User accounts, auth, and search queries
│   ├── views/
│   │   ├── auth/                   # Login & registration views
│   │   ├── layouts/                # Header, navbar, footer, modals
│   │   ├── partials/               # Reusable post, comment, and sidebar partials
│   │   ├── post/                   # Feed and edit views
│   │   ├── profile/                # Profile show and edit views
│   │   └── search/                 # Search results view
│   └── helpers.php                 # Core helpers (escaping, CSRF, avatar, reactions)
├── config/
│   └── database.php                # Database connection using PDO singleton
├── public/
│   ├── assets/
│   │   ├── css/style.css           # High-tech design system & dark mode
│   │   ├── img/logo.svg            # Vector CailaVerse brand icon
│   │   └── js/app.js               # AJAX controllers & micro-interactions
│   ├── uploads/                    # User uploaded images
│   └── index.php                   # Front controller and router
└── sql/
    └── social_app.sql              # Database schema & sample seed data
```
