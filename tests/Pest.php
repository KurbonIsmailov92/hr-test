<?php

use App\Modules\Identity\Enums\RoleName;
use App\Modules\Identity\Models\User;
use Database\Factories\UserFactory;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(Tests\TestCase::class)->use(RefreshDatabase::class)->in('Feature');
pest()->extend(Tests\TestCase::class)->in('Unit');

// Vite-сборка не нужна для тестов (иначе Blade требует manifest).
pest()->beforeEach(fn () => $this->withoutVite())->in('Feature');

/** Создаёт активного пользователя с ролью. Сидер ролей идемпотентен. */
function makeUser(string|RoleName $role = RoleName::Employee, array $attributes = []): User
{
    (new RolePermissionSeeder)->run();

    $user = User::factory()->create($attributes);
    $user->assignRole($role instanceof RoleName ? $role->value : $role);

    return $user;
}

function testPassword(): string
{
    return UserFactory::TEST_PASSWORD;
}
