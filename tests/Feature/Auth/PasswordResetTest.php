<?php

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

it('sends a reset link to a registered email', function () {
    Notification::fake();
    $user = makeUser('employee', ['email' => 'user@example.test']);

    $this->post('/forgot-password', ['email' => 'user@example.test'])
        ->assertSessionHas('status', __('hrms.password_link_sent'));

    Notification::assertSentTo($user, ResetPassword::class);
});

it('gives the same answer for an unknown email', function () {
    Notification::fake();

    $this->post('/forgot-password', ['email' => 'nobody@example.test'])
        ->assertSessionHas('status', __('hrms.password_link_sent'));

    Notification::assertNothingSent();
});

it('resets the password with a valid token and rejects weak passwords', function () {
    Notification::fake();
    $user = makeUser('employee', ['email' => 'user@example.test']);
    $this->post('/forgot-password', ['email' => 'user@example.test']);

    $token = null;
    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
        $token = $notification->token;

        return true;
    });

    $this->post('/reset-password', [
        'token' => $token, 'email' => 'user@example.test',
        'password' => 'weak', 'password_confirmation' => 'weak',
    ])->assertSessionHasErrors('password');

    $this->post('/reset-password', [
        'token' => $token, 'email' => 'user@example.test',
        'password' => 'Brand-New-Pass-77', 'password_confirmation' => 'Brand-New-Pass-77',
    ])->assertRedirect('/login');

    expect(Hash::check('Brand-New-Pass-77', $user->fresh()->password))->toBeTrue();
});

it('rejects an invalid token', function () {
    makeUser('employee', ['email' => 'user@example.test']);

    $this->post('/reset-password', [
        'token' => 'invalid', 'email' => 'user@example.test',
        'password' => 'Brand-New-Pass-77', 'password_confirmation' => 'Brand-New-Pass-77',
    ])->assertSessionHasErrors('email');
});
