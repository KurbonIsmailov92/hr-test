<?php

namespace Database\Factories;

use App\Modules\Identity\Models\Person;
use App\Modules\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<User> */
class UserFactory extends Factory
{
    protected $model = User::class;

    public const TEST_PASSWORD = 'Test-Password-123';

    public function definition(): array
    {
        return [
            'person_id' => Person::factory(),
            'email' => fake()->unique()->safeEmail(),
            'password' => self::TEST_PASSWORD,
            'status' => User::STATUS_ACTIVE,
            'locale' => 'ru',
            'remember_token' => Str::random(10),
        ];
    }

    public function blocked(): static
    {
        return $this->state(['status' => User::STATUS_BLOCKED]);
    }
}
