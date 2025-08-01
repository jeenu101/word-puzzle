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

git clone https://github.com/jeenu101/word-puzzle.git

cd word-puzzle

### 2. Install Dependencies

composer install

### 3. Create Environment File

cp .env.example .env

Update .env with your local database and app configuration.

### 4. Generate Application Key

php artisan key:generate

### 5. Run Database Migrations

php artisan migrate

### 6. Seed Admin or Sample Data

php artisan db:seed

### 7. Running Tests

php artisan test

### 8. Run the Application
php artisan serve

## Other Instructions

you can login the admin using
Email Address: admin@word-puzzle.com
Password: admin123

To play game as student you need to register..

