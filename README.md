# Trendline - Subscription Management System

A Laravel-based subscription management system with complete CRUD APIs, subscription lifecycle management, and grace period handling.

## What This Does

- Create and manage subscriptions (trials, active, past due, canceled)
- Handle payments and payment status tracking
- Automatic grace period when payment fails (3 days to retry)
- Daily automated tasks to manage subscription lifecycles
- Full REST API for all operations

## Setup

### Requirements
- PHP 8.1+
- Laravel 10+
- MySQL 8.0+

### Installation

1. Clone and install dependencies:
```bash
composer install
cp .env.example .env
php artisan key:generate
```

2. Setup database:
```bash
php artisan migrate
```

3. Test it works:
```bash
php artisan serve
```
