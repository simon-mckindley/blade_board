# 🧠 BladeBoard

**BladeBoard** is a Laravel-powered content sharing and discussion platform built with Blade templating. It supports rich post functionality, tagging, user view tracking, and flexible filtering — all with a clean, responsive UI.

---

## 🚀 Features

- 📝 **Posts** — Create and view posts with markdown support
- 🔖 **Tags** — Organize content with multiple tags per post
- 🔍 **Filtering** — Filter posts by tags, users, dates, or title
- 🧑‍💻 **User View Tracking** — Logged-in users’ views are tracked and sortable
- ❤️ **Likes & Comments** — Basic social features built-in
- 🔐 **Admin Panel** — Manage users and content with role-based access
- 📈 **Post Sorting** — Sort posts by popularity, comments, recency, or views
- 🧵 **Blade Components** — Reusable UI parts for maintainability
- ⚡ **Caching** — Tags and other data are cached to improve performance
- 🐘 **PostgreSQL** & 🛢️ **Supabase** — Used as the database backend
- 🌐 **Heroku Deployment** — Live and free-tier friendly

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12, PHP 8+
- **Frontend:** Blade, JavaScript, CSS
- **Database:** PostgreSQL (via Supabase)
- **Deployment:** Heroku (Eco Dyno)

---

## 📦 Installation

1. Clone the repo:

   ```bash
   git clone https://github.com/simon-mckindley/blade_board
   cd bladeboard

2. Install dependencies:

    composer install
    npm install && npm run dev

3. Set up your .env:

    cp .env.example .env
    php artisan key:generate

4. Set your DB credentials and run migrations:

    php artisan migrate
    php artisan db:seed

5. Serve the app:

    php artisan serve

---

## 🔐 Admin Access

Create a new super admin user via the interface or seeders to manage users and tags.

---

## 🌍 Live Demo

Heroku: <https://bladeboard-c2a2e3afe73e.herokuapp.com>

---

## 👨‍💻 Author

Simon Mckindley
[simon-mckindley.netlify.app](https://simon-mckindley.netlify.app/)
