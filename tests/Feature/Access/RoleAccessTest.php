<?php

use Inertia\Testing\AssertableInertia as Assert;

it('serves the public home page to guests', function () {
    $this->get('/')->assertOk()->assertInertia(fn (Assert $page) => $page->component('Careers/Home'));
});

it('redirects guests from every protected area to login', function (string $url) {
    $this->get($url)->assertRedirect('/login');
})->with(['/admin', '/portal', '/candidate']);

it('enforces area access by role on the backend', function (string $role, string $url, int $status) {
    $this->actingAs(makeUser($role))->get($url)->assertStatus($status);
})->with([
    // HR Admin
    ['admin', '/admin', 200],
    ['hr', '/admin', 200],
    ['recruiter', '/admin', 200],
    ['manager', '/admin', 403],
    ['employee', '/admin', 403],
    ['candidate', '/admin', 403],
    // Employee Portal
    ['employee', '/portal', 200],
    ['manager', '/portal', 200],
    ['admin', '/portal', 403],
    ['hr', '/portal', 403],
    ['candidate', '/portal', 403],
    // Career Portal: кабинет кандидата
    ['candidate', '/candidate', 200],
    ['employee', '/candidate', 403],
    ['recruiter', '/candidate', 403],
]);

it('shares only the authenticated user data the UI needs', function () {
    $user = makeUser('admin');

    $this->actingAs($user)->get('/admin')->assertInertia(
        fn (Assert $page) => $page
            ->component('Admin/Dashboard')
            ->where('auth.user.id', $user->id)
            ->where('auth.user.roles', ['admin'])
            ->missing('auth.user.password')
            ->has('stats.users'),
    );
});

it('switches locale for guests and persists it for users', function () {
    $this->post('/locale', ['locale' => 'tg'])->assertRedirect();
    expect(session('locale'))->toBe('tg');

    $user = makeUser('employee');
    $this->actingAs($user)->post('/locale', ['locale' => 'tg']);
    expect($user->fresh()->locale)->toBe('tg');
});

it('rejects unsupported locales', function () {
    $this->post('/locale', ['locale' => 'xx'])->assertSessionHasErrors('locale');
});

it('sends baseline security headers', function () {
    $this->get('/')
        ->assertHeader('X-Frame-Options', 'DENY')
        ->assertHeader('X-Content-Type-Options', 'nosniff');
});
