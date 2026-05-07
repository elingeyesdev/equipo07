<?php

namespace App\Providers;

use App\Models\Categoria;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrap();

        // Compartir categorias con el layout adminlte para el sidebar.
        View::composer('layouts.adminlte', function ($view) {
            $categorias = Categoria::orderBy('nombre')->get();
            $view->with('categorias', $categorias);
        });
    }
}
