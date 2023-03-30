# Nago

[![Build and Test (With Coverage)](https://github.com/sethsandaru/nago/actions/workflows/build-and-test.yaml/badge.svg)](https://github.com/sethsandaru/nago/actions/workflows/build-and-test.yaml)
[![codecov](https://codecov.io/gh/sethsandaru/nago/branch/main/graph/badge.svg?token=DA8TO7XCOK)](https://codecov.io/gh/sethsandaru/nago)


Nago is an API Application which provides:

- Get users list
- Get user info
- Follow a user
- Unfollow a user
- Get followers of user
- Get following users of user

Nago uses the latest Laravel version 😆

FYA if you curious why I named this Nago, Nago is [Kamen Rider Nago](https://kamenrider.fandom.com/wiki/Neon_Kurama) and I love her 🫣

## Requirements
- PHP 8.1+
- MySQL 8
- Composer (PHP package management)

## Development

### Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

### Seeder

Seed some testing data if you want. This part is optional.

```php
php arisan db:seed
```

Note: default login password is `hello123`

### Local Run

Fast 'n furious

```bash
php artisan serve
```

### Testing

```bash
composer test # without coverage
composer test-coverage # with coverage
```

- Test Structure:
  - Unit Tests: test methods of classes (small unit).
  - Feature Tests: test a whole endpoint (big one).

### Project Structure

- app: Core & Shared stuff
  - Modules: small modules of the app
    - Auth
      - Http
        - V1
          - Controllers
          - Requests
          - Resources
      - Models
      - Routes
      - Services
      - AuthServiceProvider.php
    - Users

### Helpful DevTools

Nago uses PHP-CS-FIXER to apply awesome coding standards 😉

## Endpoints

### [GET] v1/users

To get a list of users. By default, the list will be paginated (per 20 records).

Payload:

- sort_by: created_at|name|email
- sort_direction: asc|desc
- search: nullable (will search on the email & name columns)
- limit: default 20, max 100
- page: default 1

Response:

```json
{
    "data": [
        {
            "uuid": "...",
            "name": "Seth Tran",
            "email": "seth@sethphat.com"
        }
    ]
}
```

### [GET] v1/users/:userUuid

To get the info of a single user, even yourself.

```json
{
    "data": {
        "uuid": "...",
        "name": "Seth Tran",
        "email": "seth@sethphat.com"
    }
}
```

### [GET] v1/users/:userUuid/followers

To get a list followers of the given user. By default, the list will be paginated (per 20 records).

Payload:

- sort_by: followed_at|name
- sort_direction: asc|desc
- search: nullable (will search on the name column)
- limit: default 20, max 100
- page: default 1

An example for user Seth Phat, there are 2 followers:

```json
{
    "data": [
        {
            "name": "Jarek Tkaczyk",
            "followed_at": "2023-03-03 10:15:20"
        },
        {
            "name": "Taylor Otwell",
            "followed_at": "2023-03-03 10:20:25"
        }
    ]
}
```

### [GET] v1/users/:userUuid/following

To get a list following users of the given user. By default, the list will be paginated (per 20 records).

Payload:

- sort_by: followed_at|name
- sort_direction: asc|desc
- search: nullable (will search on the name column)
- limit: default 20, max 100
- page: default 1

An example for user Seth Phat, Seth Phat is following 3 users

```json
{
    "data": [
        {
            "name": "Jarek Tkaczyk",
            "followed_at": "2023-03-03 10:15:20"
        },
        {
            "name": "Taylor Otwell",
            "followed_at": "2023-03-03 10:20:25"
        },
        {
            "name": "Yone",
            "followed_at": "2023-03-04 10:25:25"
        }
    ]
}
```

### [POST] v1/users/:userUuid/follow

To follow a specific user (the authenticated user will follow the particular user).

```json
{
    "success": true
}
```

### [POST] v1/users/:userUuid/unfollow

To unfollow a specific user (the authenticated user will unfollow the particular user).

```json
{
    "success": true
}
```

## Follow Up

- Should implement a proper Authentication

## Final Words

Looking forward to becoming your new colleague soon 😉

Feel free to contact me for any tech-geek session.

Check out my GitHub Profile and my GH's Org for other cool packages:

- [Seth Phat](https://github.com/sethsandaru)
- [ShipSaaS](https://github.com/shipsaas)
