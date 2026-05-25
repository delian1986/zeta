# ZETA

## Local setup (Docker)

1. Ensure `src/.env.example` exists. On first Docker boot, the PHP entrypoint creates `src/.env` from it when `src/.env` is missing, then applies database-related variables from Compose. You can also copy it yourself before starting:

   ```bash
   cp src/.env.example src/.env
   ```

2. Start the stack (PHP-FPM, Nginx, MySQL, queue worker, phpMyAdmin):

   ```bash
   docker compose up -d --build
   ```

   The `queue` service runs `php artisan queue:work` so background jobs are processed. The `app` service runs migrations once on startup; the worker waits until the app is healthy before starting.

3. Open the app at [http://localhost:8080](http://localhost:8080) (override with `APP_PORT`).

## Local setup (without Docker, from `src/`)

```bash
cd src
composer run setup
```

This installs dependencies, requires `.env.example`, creates `.env` from it when missing, generates the app key, runs migrations, and builds front-end assets. Start a queue worker in another terminal:

```bash
cd src
php artisan queue:work
```

For a single dev command that includes a queue listener, use `composer run dev`.
