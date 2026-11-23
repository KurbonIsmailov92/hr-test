<?php

namespace App\Providers;

use App\Modules\Identity\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Модель пользователя живёт в модуле Identity.
        config(['auth.providers.users.model' => User::class]);
    }

    public function boot(): void
    {
        // Единая парольная политика (внешние сервисы проверки утечек не подключаем).
        Password::defaults(fn () => Password::min(10)->mixedCase()->numbers());

        // Запрет разрушительных artisan-команд (migrate:fresh и т.п.) на production.
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }
}
