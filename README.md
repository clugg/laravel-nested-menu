# laravel-nested-menu

Fetches menu items from a JSON URL specified in .env (`MENU_ITEMS_URL`) on an hourly basis. You may also run a manual fetch using `artisan fetch:menu-items`. Only items which are not currently stored in the database will be imported.

## Setting Up

[Laravel Sail](https://laravel.com/docs/8.x/sail) is used for local development. Sail uses [Docker](https://www.docker.com/) so you will need to make sure you have that installed (specifically, Docker Desktop). The `.env.example` is already configured for Laravel Sail.

1. Clone the repo and navigate to it in your terminal.
2. Run this in your terminal to pull in the composer dependencies:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```
3. Run `sail up` (add `-d` for detached mode)
4. Run a frontend build using `sail yarn prod`
5. Run a manual fetch using `sail artisan fetch:menu-items`

You'll need to run a queue worker in order to have the items be fetched hourly.
