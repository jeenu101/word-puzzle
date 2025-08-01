# Word Puzzle

A Laravel-based word puzzle game with role-based access for admins and students.

## 🚀 Features

- Admin dashboard for managing puzzles
- Student game interface
- Role-based route access
- Authentication system

---

## 🛠️ Project Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/jeenu101/word-puzzle.git
cd word-puzzle
composer install
cp .env.example .env
Update .env with your local database and app configuration.
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
php artisan test
