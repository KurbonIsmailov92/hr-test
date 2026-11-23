# HR-платформа

Единая HR-платформа: Career Portal (кандидаты), Employee Portal (сотрудники), HR Admin (администрирование).
Modular Monolith на Laravel + PostgreSQL + Vue 3 / Inertia. Статус разработки: см. [docs/PROGRESS.md](docs/PROGRESS.md).

## Требования

Только **Docker** с Docker Compose. PHP, Composer и Node на хосте не нужны.

## Первый запуск

```bash
./setup.sh
```

Скрипт:
1. создаёт `.env` и генерирует пароль БД;
2. собирает PHP-образ и создаёт Laravel-скелет актуальной стабильной версии, накладывая код проекта поверх него;
3. ставит зависимости (Composer, npm);
4. запускает PostgreSQL и Redis, выполняет миграции и сидеры.

Затем:

```bash
docker compose up -d
```

| Адрес | Назначение |
|---|---|
| http://localhost:8080 | Приложение |
| http://localhost:8025 | Mailpit (письма сброса пароля) |
| http://localhost:5173 | Vite dev-сервер (HMR) |

В окружении `local` сидер создаёт demo-пользователей (`admin@`, `hr@`, `recruiter@`, `manager@`, `employee@`, `candidate@example.test`).
Общий пароль печатается в консоль при первом запуске сидера. Свой пароль можно задать через `SEED_DEMO_PASSWORD` в `.env`.
На production demo-пользователи не создаются, администратор создаётся командой:

```bash
docker compose exec app php artisan hrms:create-admin admin@company.tj Фамилия Имя
```

## Тесты и проверки

```bash
docker compose exec app php artisan test        # Pest (используется БД hrms_test)
docker compose exec node npm run typecheck      # vue-tsc
docker compose exec node npm run build          # production-сборка фронтенда
```

## Структура

```
app/Modules/<Module>/{Models,Actions,Enums,Http/...}   бизнес-модули (Identity, Recruitment, Audit; остальные по этапам)
app/Http/                                              общие middleware и контроллеры дашбордов
routes/{careers,auth,portal,admin}.php                 три интерфейса + аутентификация
resources/js/{Layouts,Pages,Components,locales}        Vue 3 + TypeScript, тексты в locales/*.json
lang/{ru,tg,en}                                        серверные сообщения (валидация, аутентификация)
config/hrms.php                                        бизнес-настройки (языки, пояс, валюта)
```

Области доступа (backend, Spatie Permission + middleware `permission:`):

| Роль | `/admin` | `/portal` | `/candidate` |
|---|---|---|---|
| admin | да | нет | нет |
| hr, recruiter | да | нет | нет |
| manager, employee | нет | да | нет |
| candidate | нет | нет | да |

Один человек может иметь несколько ролей (например, руководитель: `manager` + `employee`).

## Окружения

DEV/TEST/PROD различаются только переменными окружения (`.env`, секреты в репозиторий не попадают). `phpunit.xml` принудительно использует отдельную БД `hrms_test`.
