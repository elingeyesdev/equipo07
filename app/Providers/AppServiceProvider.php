<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Categoria;

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
        // Compartir categorías con el layout adminlte para el sidebar
        View::composer('layouts.adminlte', function ($view) {
            $categorias = Categoria::orderBy('nombre')->get();
            $view->with('categorias', $categorias);
        });
    }
}
