# Event Booking System

A Laravel 12 API for categorizing events and managing seat bookings.

## Stack

- PHP 8.2
- Laravel 12
- Laravel Sanctum (token authentication)
- Pest (testing)
- Vite + Tailwind CSS

## Domain

| Model    | Description                                      |
| -------- | ------------------------------------------------ |
| User     | Authenticated users with `admin` or `user` role  |
| Category | Groups events by type                            |
| Event    | Bookable events with capacity, price, and status |
| Booking  | User reservations for events                     |

Relationships: `Category 1—* Event 1—* Booking *—1 User`

## API Endpoints

| Method | Path           | Auth          | Description              |
| ------ | -------------- | ------------- | ------------------------ |
| POST   | `/api/register` | —            | Create account + token   |
| POST   | `/api/login`    | —            | Login + token            |
| GET    | `/api/user`     | `auth:sanctum` | Current authenticated user |

## Setup

```bash
git clone <repo-url>
cd event-booking-system
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Seeded admin account: `admin@admin.com` / `admin123`

## Documentation

See [PROJECT.md](PROJECT.md) for architecture details, current status, and implementation notes (including the AuthController login pattern).
