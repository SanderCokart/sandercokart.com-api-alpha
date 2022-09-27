<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->setRootUrl();

        $this->routes(function () {
            Route::middleware(['api'])->group(function () {
                Route::namespace($this->namespace)
                    ->group(base_path('routes/guest.php'));
                Route::namespace($this->namespace)
                    ->middleware(['auth:sanctum'])
                    ->group(base_path('routes/authenticated.php'));
                Route::namespace($this->namespace)
                    ->middleware(['auth:sanctum', 'verified'])
                    ->group(base_path('routes/verified.php'));
            });
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(120)->by($this->generateKey($request));
        });

        RateLimiter::for('credentials', function (Request $request) {
            return Limit::perMinutes(5, 1)->by($this->generateKey($request, true));
        });


    }

    protected function setRootUrl(): void
    {
        URL::forceRootUrl(config('app.url'));
    }


    private function generateKey(Request $request, ?bool $useRouteWithinKey = false): ?string
    {
        if ($useRouteWithinKey)
            return $request->user()?->id . '-' . $request->route()->uri ?: $request->ip() . '-' . $request->route()->uri;
        return $request->user()?->id ?: $request->ip();
    }
}
