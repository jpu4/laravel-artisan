# Laravel Artisan Commands

A Laravel package providing reusable artisan commands for common development tasks across multiple projects.

## Installation

You can install the package via composer:

```bash
composer require machus/laravel-artisan
```

The service provider will be automatically registered via Laravel's package discovery.

## Available Commands

### `clear:all`

Runs all Laravel clear commands in one go:

```bash
php artisan clear:all
```

This command will clear:
- Application cache (`cache:clear`)
- Configuration cache (`config:clear`)
- Route cache (`route:clear`)
- Compiled views (`view:clear`)
- Compiled class file (`clear-compiled`)

### `reset`

Completely resets your application by clearing all caches and running fresh migrations with seeding:

```bash
php artisan reset
```

This command will:
1. Run `clear:all` to clear all caches
2. Run `migrate:fresh --seed --force` to reset the database

**Warning:** This command will drop all tables and re-run migrations. Use with caution in production environments.

### User Management Commands

#### `user:create`

Create a new user interactively or with options:

```bash
# Interactive mode
php artisan user:create

# With options
php artisan user:create --name="John Doe" --email="john@example.com" --password="secret123"
```

#### `user:list`

List all users with optional search and limit:

```bash
# List users (default: 50)
php artisan user:list

# Search by name or email
php artisan user:list --search="john"

# Limit results
php artisan user:list --limit=10
```

#### `user:delete`

Delete a user by ID or email:

```bash
# Delete by ID
php artisan user:delete 1

# Delete by email
php artisan user:delete john@example.com

# Skip confirmation
php artisan user:delete 1 --force
```

**Note:** All user commands require Laravel's authentication framework to be installed. They will show an error if the User model is not found.

## Usage

After installation, the commands are immediately available in your Laravel application:

```bash
# Clear all caches
php artisan clear:all

# Reset application (clear caches + fresh migrations with seeding)
php artisan reset

# User management
php artisan user:create
php artisan user:list
php artisan user:delete 1
```

## Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x

## Credits

Built using [spatie/laravel-package-tools](https://github.com/spatie/laravel-package-tools)

## License

The MIT License (MIT).
