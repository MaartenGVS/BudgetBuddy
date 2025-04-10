# BudgetBuddy

Welcome to BudgetBuddy, your go-to application for managing your budget and finances efficiently. This guide will help you set up the laravel project and get it running on your local machine. 

## Table of Contents

- [BudgetBuddy](#budgetbuddy)
    - [Table of Contents](#table-of-contents)
    - [Project Setup](#project-setup)
        - [Clone the repository](#clone-the-repository)
        - [Install PHP dependencies](#install-php-dependencies)
        - [Set up environment configuration](#set-up-environment-configuration)
        - [Generate application key](#generate-application-key)
        - [Configure database](#configure-database)
        - [Run database migrations](#run-database-migrations)
        - [Seed the database](#seed-the-database)
        - [Generate JWT secret](#generate-jwt-secret)
        - [Update `.env` for CSRF and JWT settings](#update-env-for-csrf-and-jwt-settings)

## Project Setup

### Clone the repository

Use the following command to clone the repository:

```bash
    git clone git@gitlab.ti.howest.be:ti/2023-2024/s4/web-frameworks/projects/maarten-van-santen/api.git
```

### Install PHP dependencies

Use Composer to install PHP dependencies:
```bash
    composer install
```

### Set up environment configuration

Copy the .env.example file to .env:
```bash
    copy .env.example to .env
```

### Generate application key

Generate the application key using Artisan:

```bash
    php artisan key:generate
```

### Configure database

Change the database connection settings in the .env file to use SQLite:

```bash
    DB_CONNECTION=sqlite
    DB_DATABASE=/absolute/path/to/database/database.sqlite
```

### Run database migrations

Migrate the database using Artisan:

```bash
    php artisan migrate
```

### Seed the database

Seed the database with initial data:

```bash
    php artisan db:seed
```

### Generate JWT secret

Generate the JWT secret:

```bash
    php artisan jwt:secret
```

### Update .env for CSRF and JWT settings

Add the following lines to the .env file, under the JWT_SECRET line:

```bash
    CSRF_TOKEN_LENGTH=128
    JWT_COOKIE_TTL=60
```
