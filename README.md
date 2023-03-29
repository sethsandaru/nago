# Nago

Nago is an API Application which provides:

- Authentication
- Get users list
- Get user info
- Follow a user
- Unfollow a user

Nago uses the latest Laravel version 😆

FYA if you curious why I named this Nago, Nago is [Kamen Rider Nago](https://kamenrider.fandom.com/wiki/Neon_Kurama) and I love her 

## Requirements
- PHP 8.1+
- MySQL 8
- Composer (PHP package management)
🫣
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

Note: you need to log in before requesting to other endpoints.

Remember to provide the `AUTHORIZATION: Bearer {token}` header.

### [POST] v1/auth

To login into the system.

Payload: 

- email: string
- password: string

Response:

- OK: `{"access_token": "xxxx"}`
- Error: `{"error": "The email field is required"}`

### [GET] v1/users

To get a list of users. By default, the list will be paginated (per 20 records).

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

```json
{
    "data": [
        {
            "uuid": "...",
            "name": "Jarek Tkaczyk"
        },
        {
            "uuid": "...",
            "name": "Taylor Otwell"
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

## Final Words

Looking forward to becoming your new colleague soon 😉

Feel free to contact me for any tech-geek session.

Check out my GitHub Profile and my GH's Org for other cool packages:

- [Seth Phat](https://github.com/sethsandaru)
- [ShipSaaS](https://github.com/shipsaas)
