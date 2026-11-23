<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // Три интерфейса платформы: публичный/кандидат, личный кабинет сотрудника, административная панель.
        web: [
            __DIR__.'/../routes/careers.php',
            __DIR__.'/../routes/auth.php',
            __DIR__.'/../routes/portal.php',
            __DIR__.'/../routes/admin.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SecurityHeaders::class,
            SetLocale::class,
            HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

        // Уже вошедшего пользователя отправляем на его стартовую страницу.
        $middleware->redirectUsersTo(fn (Request $request) => $request->user()?->homeUrl() ?? '/');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $e, Request $request) {
            $status = $response->getStatusCode();

            // В local/testing оставляем стандартные страницы отладки Laravel.
            if (! app()->environment(['local', 'testing']) && in_array($status, [403, 404, 500, 503], true)) {
                return Inertia::render('Error', ['status' => $status])
                    ->toResponse($request)
                    ->setStatusCode($status);
            }

            // Истёкшая сессия/CSRF-токен: возвращаем на форму с понятным сообщением.
            if ($status === 419) {
                return back()->with('error', __('hrms.page_expired'));
            }

            return $response;
        });
    })->create();
