<?php

namespace App\Modules\Identity\Actions;

use App\Modules\Identity\Enums\RoleName;
use App\Modules\Identity\Models\Person;
use App\Modules\Identity\Models\User;
use Illuminate\Support\Facades\DB;

class CreateUserAction
{
    /**
     * @param  array{last_name: string, first_name: string, middle_name?: ?string, phone?: ?string}  $person
     * @param  list<RoleName>  $roles
     */
    public function __invoke(array $person, string $email, string $password, array $roles, string $locale = 'ru'): User
    {
        return DB::transaction(function () use ($person, $email, $password, $roles, $locale) {
            $personModel = Person::create($person);

            $user = User::create([
                'person_id' => $personModel->id,
                'email' => mb_strtolower(trim($email)),
                'password' => $password, // хешируется кастом 'hashed'
                'locale' => $locale,
            ]);

            $user->assignRole(array_map(fn (RoleName $role) => $role->value, $roles));

            return $user;
        });
    }
}
