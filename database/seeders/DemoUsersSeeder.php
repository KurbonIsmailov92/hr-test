<?php

namespace Database\Seeders;

use App\Modules\Identity\Actions\CreateUserAction;
use App\Modules\Identity\Enums\RoleName;
use App\Modules\Identity\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** Демонстрационные пользователи. Только для local/demo, на production не выполняется. */
class DemoUsersSeeder extends Seeder
{
    public function run(CreateUserAction $createUser): void
    {
        if (! app()->environment(['local', 'demo'])) {
            $this->command?->warn('DemoUsersSeeder пропущен: окружение не local/demo.');

            return;
        }

        $password = env('SEED_DEMO_PASSWORD') ?: Str::password(16, symbols: false);

        $demo = [
            ['admin@example.test', 'Админов', 'Админ', [RoleName::Admin]],
            ['hr@example.test', 'Кадрова', 'Хадича', [RoleName::Hr]],
            ['recruiter@example.test', 'Рекрутов', 'Рустам', [RoleName::Recruiter]],
            ['manager@example.test', 'Руководов', 'Фаррух', [RoleName::Manager, RoleName::Employee]],
            ['employee@example.test', 'Сотрудникова', 'Нигина', [RoleName::Employee]],
            ['candidate@example.test', 'Кандидатов', 'Умед', [RoleName::Candidate]],
        ];

        $created = [];
        foreach ($demo as [$email, $last, $first, $roles]) {
            if (User::where('email', $email)->exists()) {
                continue;
            }
            $createUser(['last_name' => $last, 'first_name' => $first], $email, $password, $roles);
            $created[] = $email;
        }

        if ($created) {
            $this->command?->info('Demo-пользователи созданы: '.implode(', ', $created));
            $this->command?->info("Общий пароль demo-пользователей: {$password}");
        }
    }
}
