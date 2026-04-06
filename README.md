# DevHub — Developer Tools & Resources Platform

A full-featured Laravel 11 developer community platform with blog, code snippets, online tools, newsletter system, and admin panel.

## Features

- Blog with categories, tags, comments, and social sharing
- 8 interactive developer tools (JSON Formatter, Regex Tester, Password Generator, etc.)
- Code snippets library with syntax highlighting (Prism.js)
- Newsletter subscription system with welcome emails
- Sponsored ads management system
- Full admin panel (dashboard, posts, sponsors, subscribers, users)
- SEO optimized (meta tags, sitemap.xml, structured data, robots.txt)
- Google AdSense integration ready
- Affiliate link system
- Global search across posts, tools, and snippets
- Role-based access control (admin, author, user)

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ & npm
- SQLite (default) or MySQL 8+

## Installation

```bash
# Clone the repository
git clone <repo-url> developer-hub
cd developer-hub

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Create database (SQLite)
touch database/database.sqlite

# Run migrations and seeders
php artisan migrate --seed

# Create storage symlink
php artisan storage:link

# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

The application will be available at `http://localhost:8000`.

## Admin Panel

Access the admin panel at `/admin` with:

- **Email:** admin@devhub.com
- **Password:** admin123

The admin panel provides:
- Dashboard with stats and recent activity
- Posts CRUD management
- Sponsored ads management
- Newsletter subscriber management (with CSV export)
- User role management

## Seeded Data

The seeders create:
- 1 admin user (admin@devhub.com)
- 6 categories (Web Development, Laravel, JavaScript, CSS & Design, DevTools, Career)
- 8 developer tools
- 3 roles (admin, author, user)

## Deployment

```bash
chmod +x deploy.sh
./deploy.sh
```

Or manually:
```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan migrate --force
```

## Tech Stack

- **Backend:** Laravel 11, PHP 8.2+
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Auth:** Laravel Breeze
- **Permissions:** spatie/laravel-permission
- **SEO:** artesaos/seotools, spatie/laravel-sitemap
- **Markdown:** league/commonmark
- **Syntax Highlighting:** Prism.js (CDN)
