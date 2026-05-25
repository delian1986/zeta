#!/bin/bash
set -e

cd /var/www/html

wait_for_mysql() {
  echo "Waiting for MySQL..."
  local max=30
  local i=0
  until php -r "
    \$host = getenv('DB_HOST') ?: 'mysql';
    \$user = getenv('DB_USERNAME') ?: 'zeta';
    \$pass = getenv('DB_PASSWORD') ?: 'secret';
    try {
      new PDO(\"mysql:host=\$host;port=3306\", \$user, \$pass);
      exit(0);
    } catch (Exception \$e) {
      exit(1);
    }
  " 2>/dev/null; do
    i=$((i + 1))
    if [ "$i" -ge "$max" ]; then
      echo "MySQL not ready after ${max} attempts."
      exit 1
    fi
    sleep 2
  done
  echo "MySQL is ready."
}

ensure_env_file() {
  if [ ! -f .env ]; then
    if [ -f .env.example ]; then
      cp .env.example .env
      echo "Created .env from .env.example"
    else
      echo "No .env or .env.example found in /var/www/html"
      exit 1
    fi
  fi

  update_env() {
    local key="$1"
    local value="$2"
    if grep -q "^${key}=" .env; then
      sed -i "s|^${key}=.*|${key}=${value}|" .env
    else
      echo "${key}=${value}" >> .env
    fi
  }

  update_env APP_URL "${APP_URL:-http://localhost:8080}"
  update_env DB_CONNECTION "${DB_CONNECTION:-mysql}"
  update_env DB_HOST "${DB_HOST:-mysql}"
  update_env DB_PORT "${DB_PORT:-3306}"
  update_env DB_DATABASE "${DB_DATABASE:-zeta}"
  update_env DB_USERNAME "${DB_USERNAME:-zeta}"
  update_env DB_PASSWORD "${DB_PASSWORD:-secret}"
}

bootstrap_laravel_common() {
  if [ ! -f composer.json ]; then
    echo "Laravel not found. Run: docker compose run --rm app composer create-project laravel/laravel ."
    exit 1
  fi

  echo "Installing Composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader

  if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    echo "Generating application key..."
    php artisan key:generate --force --no-interaction
  fi

  chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
  chmod -R ug+rwx storage bootstrap/cache
}

bootstrap_laravel_app() {
  bootstrap_laravel_common
  echo "Running migrations..."
  php artisan migrate --force --no-interaction
  touch /tmp/zeta-app-ready
}

wait_for_mysql
ensure_env_file

if [ "${1:-}" = "queue-worker" ]; then
  shift
  bootstrap_laravel_common
  echo "Starting queue worker..."
  exec php artisan queue:work "$@"
fi

bootstrap_laravel_app

exec docker-php-entrypoint php-fpm
