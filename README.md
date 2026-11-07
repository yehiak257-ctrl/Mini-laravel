# Mini Laravel

A small PHP framework built from scratch to understand how Laravel works internally: autoloading, routing, the request lifecycle, dependency injection, views, and a simple database layer.

This is a **learning project**. It is not meant for production use.

## What it covers

- **Autoloading**: a hand-written PSR-4 style autoloader using `spl_autoload_register`, mapping `App\Models\User` to `app/Models/User.php`
- **Single entry point**: every request goes through `public/index.php`
- **Request**: an object that wraps the incoming request (path, query string)
- **Router**: registers routes with `get()` and runs them with `dispatch()`, including route parameters like `/users/{id}`
- **Controllers**: routes can point to `[UserController::class, 'method']`
- **Service container**: reads type hints with Reflection and builds dependencies automatically (constructor injection and method injection, resolved recursively)
- **Views**: plain PHP templates rendered with `extract()` and output buffering, with output escaped using `htmlspecialchars`
- **Database**: a thin `PDO` wrapper using prepared statements (SQLite)
- **Model**: a simple `User` class that holds the SQL for the `users` table

## Request lifecycle

```
Browser
  -> public/index.php
  -> Router is created, routes/web.php registers the routes
  -> Request is created, Router receives its path
  -> Router matches a route and extracts parameters like {id}
  -> Container builds the Controller and its dependencies
  -> Controller method runs and returns a string (or a rendered view)
  -> index.php prints the result
```

## Project structure

```
mini-laravel/
├── app/
│   ├── Controllers/
│   │   └── UserController.php
│   ├── Models/
│   │   └── User.php
│   ├── Container.php
│   ├── Database.php
│   ├── Request.php
│   ├── Router.php
│   └── View.php
├── database/
│   └── setup.php
├── public/
│   └── index.php
├── routes/
│   └── web.php
├── views/
│   └── users.php
└── autoload.php
```

## Example

Routes are registered in `routes/web.php`:

```php
$router->get('/', function () {
    return 'Home page';
});

$router->get('/users', [UserController::class, 'index']);
$router->get('/users/{id}', [UserController::class, 'show']);
```

A controller asks for what it needs, and the container provides it:

```php
class UserController
{
    public function index(Request $request, User $users)
    {
        return View::make('users', [
            'page'  => $request->query('page', 1),
            'users' => $users->all(),
        ]);
    }

    public function show($id, User $users)
    {
        $user = $users->find($id);

        return $user === null ? 'User not found' : 'User: ' . htmlspecialchars($user['name']);
    }
}
```

The container sees that `index` needs a `Request` and a `User`, sees that `User` needs a `Database`, and builds the whole chain.

## Requirements

- PHP 8.0 or higher
- The `pdo_sqlite` extension enabled

## Running it

```bash
git clone <repository-url>
cd mini-laravel

# create the SQLite database with a users table and sample rows (run once)
php database/setup.php

# start the built-in server
php -S localhost:8000 -t public
```

Then open:

```
http://localhost:8000/users
http://localhost:8000/users/1
http://localhost:8000/users?page=2
```

## How it compares to Laravel

| This project         | Laravel                              |
| -------------------- | ------------------------------------ |
| `autoload.php`       | Composer autoloader and PSR-4        |
| `routes/web.php`     | `routes/web.php` with `Route::get()` |
| `Request`            | `Illuminate\Http\Request`            |
| `Router`             | `Illuminate\Routing\Router`          |
| `Container`          | Service Container                    |
| `View::make()`       | `view()` with Blade                  |
| `Database` + `User`  | `DB` facade and Eloquent             |

## Not implemented

Middleware, service providers, facades, migrations, validation, authentication, CSRF protection, tests, and centralized error handling. Eloquent features such as relationships are also not covered.

## What I learned

- How a framework turns a URL into a controller call
- How autoloading maps namespaces to files
- How a container uses Reflection to inject dependencies
- Why constructor injection makes classes easier to change and test
- Why user input must be escaped in views and bound as parameters in SQL
