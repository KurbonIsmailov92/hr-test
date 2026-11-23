<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'appName' => config('app.name'),
            'locale' => app()->getLocale(),
            'locales' => config('hrms.locales'),
            'displayTimezone' => config('hrms.display_timezone'),
            'auth' => ['user' => fn () => $this->authUser($request)],
            'flash' => fn () => [
                'success' => $request->session()->get('success'),
                'status' => $request->session()->get('status'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }

    /** Только то, что нужно интерфейсу. Проверка прав на frontend лишь для отображения, настоящая проверка на backend. */
    private function authUser(Request $request): ?array
    {
        $user = $request->user()?->loadMissing('person');

        if (! $user) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->person->full_name,
            'email' => $user->email,
            'locale' => $user->locale,
            'roles' => $user->getRoleNames()->all(),
            'permissions' => $user->getAllPermissions()->pluck('name')->all(),
        ];
    }
}
