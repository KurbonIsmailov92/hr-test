<?php

use App\Modules\Audit\Models\AuditLog;
use Inertia\Testing\AssertableInertia as Assert;

it('shows the login page to guests', function () {
    $this->get('/login')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Auth/Login'));
});

it('redirects each role to its own start page after login', function (string $role, string $expected) {
    $user = makeUser($role, ['email' => 'user@example.test']);

    $this->post('/login', ['email' => 'user@example.test', 'password' => testPassword()])
        ->assertRedirect($expected);

    $this->assertAuthenticatedAs($user);
})->with([
    ['admin', '/admin'],
    ['hr', '/admin'],
    ['recruiter', '/admin'],
    ['manager', '/portal'],
    ['employee', '/portal'],
    ['candidate', '/candidate'],
]);

it('treats email case-insensitively', function () {
    makeUser('employee', ['email' => 'mixed@example.test']);

    $this->post('/login', ['email' => 'MIXED@Example.Test', 'password' => testPassword()])
        ->assertRedirect('/portal');
});

it('rejects a wrong password', function () {
    makeUser('employee', ['email' => 'user@example.test']);

    $this->post('/login', ['email' => 'user@example.test', 'password' => 'wrong-password'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('does not let blocked users in and does not reveal that they are blocked', function () {
    $user = makeUser('employee', ['email' => 'blocked@example.test']);
    $user->update(['status' => 'blocked']);

    $this->post('/login', ['email' => 'blocked@example.test', 'password' => testPassword()])
        ->assertSessionHasErrors(['email' => trans('auth.failed')]);

    $this->assertGuest();
});

it('locks out after repeated failed attempts', function () {
    makeUser('employee', ['email' => 'user@example.test']);

    foreach (range(1, 5) as $attempt) {
        $this->post('/login', ['email' => 'user@example.test', 'password' => 'wrong-password']);
    }

    // Даже верный пароль не принимается, пока действует блокировка.
    $this->post('/login', ['email' => 'user@example.test', 'password' => testPassword()])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
    expect(AuditLog::where('action', 'auth.login_throttled')->exists())->toBeTrue();
});

it('logs out and invalidates the session', function () {
    $user = makeUser('employee');

    $this->actingAs($user)->post('/logout')->assertRedirect('/');

    $this->assertGuest();
});

it('writes login events to the audit log', function () {
    $user = makeUser('employee', ['email' => 'user@example.test']);

    $this->post('/login', ['email' => 'user@example.test', 'password' => testPassword()]);

    expect(AuditLog::where('action', 'auth.login')->where('actor_id', $user->id)->exists())->toBeTrue();
});

it('sends already authenticated users away from the login page', function () {
    $this->actingAs(makeUser('candidate'))->get('/login')->assertRedirect('/candidate');
});
