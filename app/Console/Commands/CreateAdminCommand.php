<?php

namespace App\Console\Commands;

use App\Modules\Audit\Audit;
use App\Modules\Identity\Actions\CreateUserAction;
use App\Modules\Identity\Enums\RoleName;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminCommand extends Command
{
    protected $signature = 'hrms:create-admin {email} {last_name} {first_name}';

    protected $description = 'Создать пользователя с ролью admin (пароль вводится интерактивно и не попадает в историю shell)';

    public function handle(CreateUserAction $createUser): int
    {
        $password = $this->secret('Пароль');
        $confirmation = $this->secret('Повторите пароль');

        $validator = Validator::make(
            [
                'email' => mb_strtolower(trim($this->argument('email'))),
                'password' => $password,
                'password_confirmation' => $confirmation,
            ],
            [
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'confirmed', Password::defaults()],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = $createUser(
            ['last_name' => $this->argument('last_name'), 'first_name' => $this->argument('first_name')],
            $this->argument('email'),
            $password,
            [RoleName::Admin],
        );

        Audit::record('admin.created_via_cli', $user, actor: $user);
        $this->info("Администратор создан: {$user->email}");

        return self::SUCCESS;
    }
}
