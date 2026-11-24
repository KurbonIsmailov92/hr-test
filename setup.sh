#!/usr/bin/env bash
# Первичная установка: создаёт Laravel-скелет, накладывает код проекта, ставит зависимости, мигрирует БД.
# Требуется только Docker (PHP/Composer/Node на хосте не нужны). Скрипт идемпотентен.
set -euo pipefail
cd "$(dirname "$0")"

command -v docker >/dev/null 2>&1 || { echo "Нужен Docker (https://docs.docker.com/get-docker/)"; exit 1; }

export HOST_UID="$(id -u)" HOST_GID="$(id -g)"

if [ ! -f .env ]; then
  cp .env.example .env
  PASS="$(head -c 24 /dev/urandom | od -An -tx1 | tr -d ' \n')"
  sed -i.bak "s/^DB_PASSWORD=.*/DB_PASSWORD=${PASS}/" .env && rm -f .env.bak
  sed -i.bak "s/^HOST_UID=.*/HOST_UID=${HOST_UID}/; s/^HOST_GID=.*/HOST_GID=${HOST_GID}/" .env && rm -f .env.bak
  echo ">> .env создан, пароль БД сгенерирован"
fi

echo ">> Сборка PHP-образа"
docker compose build app

if [ ! -f artisan ]; then
  echo ">> Создание Laravel-скелета (актуальная стабильная версия)"
  docker compose run --rm --no-deps app sh -c '
    composer create-project laravel/laravel /tmp/skeleton --no-interaction --no-scripts --prefer-dist &&
    cp -rn /tmp/skeleton/. /var/www/html/'
fi

# Удаляем файлы скелета, заменённые структурой проекта
rm -f vite.config.js resources/js/app.js resources/js/bootstrap.js \
      database/migrations/0001_01_01_000000_create_users_table.php \
      tests/Feature/ExampleTest.php tests/Unit/ExampleTest.php \
      app/Models/User.php resources/views/welcome.blade.php routes/web.php

echo ">> PHP-зависимости"
docker compose run --rm --no-deps app composer require inertiajs/inertia-laravel spatie/laravel-permission --no-interaction
docker compose run --rm --no-deps app composer config allow-plugins.pestphp/pest-plugin true
docker compose run --rm --no-deps app composer require --dev pestphp/pest pestphp/pest-plugin-laravel --with-all-dependencies --no-interaction

echo ">> Ключ приложения, конфиг permission, языковые файлы"
docker compose run --rm --no-deps app php artisan key:generate --force
docker compose run --rm --no-deps app php artisan vendor:publish --tag=permission-config --no-interaction
docker compose run --rm --no-deps app php artisan lang:publish --no-interaction

echo ">> JS-зависимости"
docker compose run --rm --no-deps --entrypoint sh node -c "
  npm install &&
  npm install vue vue-i18n @inertiajs/vue3 &&
  npm install -D @vitejs/plugin-vue typescript vue-tsc @types/node &&
  npm pkg set scripts.typecheck='vue-tsc --noEmit'"

echo ">> Запуск БД/Redis и миграции"
docker compose up -d db redis mailpit
docker compose run --rm app php artisan migrate --seed --force

cat <<MSG

Готово. Дальше:
  docker compose up -d                       # запуск всех сервисов
  открыть http://localhost:8080              # публичный сайт
  открыть http://localhost:8025              # Mailpit (письма сброса пароля)
  docker compose exec app php artisan test   # автотесты
Учётные записи demo-пользователей и их пароль смотрите в выводе миграции выше (seeder).
MSG
