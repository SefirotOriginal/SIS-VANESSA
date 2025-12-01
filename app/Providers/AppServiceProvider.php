<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as FrameworkVerifyCsrf;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Auth\AuthenticationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar alias de middleware requeridos por spatie/laravel-permission
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('role', \Spatie\Permission\Middleware\RoleMiddleware::class);
        $router->aliasMiddleware('permission', \Spatie\Permission\Middleware\PermissionMiddleware::class);
        $router->aliasMiddleware('role_or_permission', \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class);

        // Registrar handlers para excepciones de autenticación y permisos
        $handler = $this->app->make(ExceptionHandlerContract::class);

        // Excluir rutas de sincronización del chequeo CSRF a nivel de framework
        // Esto permite que instalaciones locales realicen POST a /sync/* sin token CSRF.
        if (class_exists(FrameworkVerifyCsrf::class)) {
            FrameworkVerifyCsrf::except(['sync/*']);
        }

        // Cuando un usuario no tiene permiso (Spatie UnauthorizedException)
        if (method_exists($handler, 'renderable')) {
            $handler->renderable(function (UnauthorizedException $e, $request) {
                if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                    return response()->json(['message' => $e->getMessage() ?: 'No autorizado.'], 403);
                }

                return redirect()->route('error.permission')->with('error', $e->getMessage() ?: 'No tienes permiso para acceder a esta sección.');
            });

            // Cuando el usuario no está autenticado
            $handler->renderable(function (AuthenticationException $e, $request) {
                if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                    return response()->json(['message' => 'No autenticado.'], 401);
                }

                return redirect()->guest(route('login'));
            });
        }
    }
}
