<?php

use App\Modules\Identity\Models\User;
use App\Modules\Recruitment\Models\CandidateProfile;

function validRegistration(array $overrides = []): array
{
    return array_merge([
        'last_name' => 'Рахимов',
        'first_name' => 'Анвар',
        'middle_name' => '',
        'phone' => '+992 900 00 00 00',
        'email' => 'Anvar@Example.Test',
        'password' => 'Strong-Pass-2026',
        'password_confirmation' => 'Strong-Pass-2026',
        'consent' => true,
        'website' => '',
    ], $overrides);
}

beforeEach(fn () => (new Database\Seeders\RolePermissionSeeder)->run());

it('registers a candidate with person, user, role and recorded consent', function () {
    $this->post('/register', validRegistration())->assertRedirect('/candidate');

    $user = User::where('email', 'anvar@example.test')->firstOrFail();

    expect($user->hasRole('candidate'))->toBeTrue()
        ->and($user->person->last_name)->toBe('Рахимов');

    $profile = CandidateProfile::where('person_id', $user->person_id)->firstOrFail();
    expect($profile->consent_version)->toBe(config('hrms.consent_version'))
        ->and($profile->consent_given_at)->not->toBeNull();

    $this->assertAuthenticatedAs($user);
});

it('requires consent to personal data processing', function () {
    $this->post('/register', validRegistration(['consent' => false]))->assertSessionHasErrors('consent');

    expect(User::count())->toBe(0);
});

it('rejects weak passwords', function (string $password) {
    $this->post('/register', validRegistration(['password' => $password, 'password_confirmation' => $password]))
        ->assertSessionHasErrors('password');
})->with(['short' => 'Aa1', 'no digits' => 'OnlyLettersHere', 'no uppercase' => 'lowercase-only-123']);

it('rejects a duplicate email regardless of letter case', function () {
    makeUser('employee', ['email' => 'anvar@example.test']);

    $this->post('/register', validRegistration(['email' => 'ANVAR@example.test']))->assertSessionHasErrors('email');
});

it('rejects submissions that fill the honeypot field', function () {
    $this->post('/register', validRegistration(['website' => 'http://spam.example']))->assertSessionHasErrors('website');

    expect(User::count())->toBe(0);
});

it('does not leave partial records when registration fails midway', function () {
    // Сбой на последнем шаге транзакции: версия согласия длиннее колонки (20 символов).
    config(['hrms.consent_version' => str_repeat('x', 50)]);

    expect(fn () => $this->withoutExceptionHandling()->post('/register', validRegistration()))
        ->toThrow(Illuminate\Database\QueryException::class);

    // Вложенная транзакция откатывается через savepoint: Person и User не должны остаться.
    expect(User::count())->toBe(0)
        ->and(Illuminate\Support\Facades\DB::table('persons')->count())->toBe(0);
});
