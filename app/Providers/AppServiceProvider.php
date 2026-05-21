<?php

namespace App\Providers;

use App\Models\Categoria;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        if (! $this->app->runningInConsole()) {
            $request = $this->app->make('request');

            $scheme = $request->headers->get('x-forwarded-proto', $request->getScheme());
            $host = $request->headers->get('x-forwarded-host', $request->getHost());
            $port = $request->headers->get('x-forwarded-port', $request->getPort());

            if ($host) {
                $host = explode(',', $host)[0];

                if (! str_contains($host, ':') && $port) {
                    $defaultPort = $scheme === 'https' ? 443 : 80;
                    if ((int) $port !== $defaultPort) {
                        $host .= ':' . $port;
                    }
                }

                $rootUrl = $scheme . '://' . $host;
                URL::forceRootUrl($rootUrl);

                if ($scheme === 'https') {
                    URL::forceScheme('https');
                }
            }
        }

        Paginator::useBootstrap();

        // Compartir categorias con el layout adminlte para el sidebar.
        View::composer('layouts.adminlte', function ($view) {
            $categorias = Categoria::orderBy('nombre')->get();
            $view->with('categorias', $categorias);
        });
    }
}
