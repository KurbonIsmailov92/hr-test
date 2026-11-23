# Прогресс

Статусы: TODO, IN PROGRESS, BLOCKED, DONE.

## Этап 0: Анализ и архитектура: DONE
Архитектура и решения согласованы (см. DECISIONS.md). Остальные документы (ROADMAP, BACKLOG, ARCHITECTURE, DATABASE) ещё не оформлены в файлы: TODO.

## Этап 1: Foundation: IN PROGRESS (код написан, запуск и тесты не выполнялись)

Код подготовлен в среде без PHP, Composer, Docker и сети, поэтому **ни миграции, ни автотесты, ни сборка фронтенда не запускались**. Этап закрывается после выполнения проверок ниже.

| Задача | Статус |
|---|---|
| Docker Compose (php-fpm, nginx, queue, node, postgres, redis, mailpit) | IN PROGRESS |
| `setup.sh`: скелет Laravel + зависимости + миграции | IN PROGRESS |
| Миграции: persons, users, роли/права (UUID), candidate_profiles, audit_logs | IN PROGRESS |
| Логин, выход, rate limiting, блокировка пользователей | IN PROGRESS |
| Регистрация кандидата (согласие ПДн, honeypot, throttle) | IN PROGRESS |
| Сброс пароля по email | IN PROGRESS |
| RBAC: 6 ролей, разрешения, middleware на трёх группах маршрутов | IN PROGRESS |
| Layouts (Auth, Careers, Admin, Portal), UI-компоненты, i18n RU/TG | IN PROGRESS |
| Обработка ошибок (Inertia-страница), логирование, security headers | IN PROGRESS |
| Сидеры (роли; demo-пользователи только local/demo), команда `hrms:create-admin` | IN PROGRESS |
| Автотесты Pest (вход, регистрация, сброс пароля, доступ по ролям, аудит) | IN PROGRESS |

### Acceptance Criteria (отметить после запуска)
- [ ] `./setup.sh` завершается без ошибок
- [ ] `docker compose exec app php artisan test` проходит полностью
- [ ] `npm run typecheck` и `npm run build` проходят
- [ ] Вход под каждой demo-ролью ведёт в свою область; чужие области возвращают 403
- [ ] Регистрация кандидата создаёт Person, User, CandidateProfile и фиксирует согласие
- [ ] Письмо сброса пароля видно в Mailpit
- [ ] Переключение RU/TG работает

### Известные ограничения
- Версии PHP 8.3, PostgreSQL 16, Redis 7, Node 22 выбраны без проверки актуальных релизов (не было сети): подтвердить.
- Laravel ставится «актуальный стабильный» через `composer create-project`; код написан под Laravel 11/12. При Laravel 13 возможны правки.
- Подтверждение email не реализовано (в backlog: важно для защиты от спама).
- Таджикские переводы выполнены без носителя языка: требуют проверки.
- Текст согласия на обработку ПДн не утверждён юристом.
- 2FA не реализована (решение заказчика).
- Horizon и Reverb не подключены (Этапы 10 и 12); очередь на `queue:work`.
- Content-Security-Policy не настроена (Этап 12).

## Этапы 2–12: TODO
