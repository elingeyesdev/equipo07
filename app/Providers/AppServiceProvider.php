<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Models\Categoria;
use App\Models\PedidoDetalle;
use App\Models\SolicitudVendedor;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
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

        View::composer(['layouts.adminlte', 'layouts.public'], function ($view) {
            $counts = [
                'navCartCount' => 0,
                'navPendingRequestsCount' => 0,
                'navOrderAlertsCount' => 0,
                'navSellerApplicationsCount' => 0,
            ];

            if (Auth::check()) {
                $user = Auth::user();

                $counts['navCartCount'] = (int) CartItem::where('user_id', $user->id)->sum('cantidad');

                $counts['navOrderAlertsCount'] = PedidoDetalle::query()
                    ->whereHas('pedido', fn ($query) => $query->where('user_id', $user->id))
                    ->where('estado_solicitud', 'aceptada')
                    ->where('estado_transporte', 'esperando_confirmacion')
                    ->whereNull('recepcion_confirmada_at')
                    ->count();

                if ($user->isAdmin()) {
                    $counts['navPendingRequestsCount'] = PedidoDetalle::where('estado_solicitud', 'pendiente')->count();
                    $counts['navSellerApplicationsCount'] = SolicitudVendedor::where('estado', 'pendiente')->count();
                } elseif ($user->isVendedor()) {
                    $counts['navPendingRequestsCount'] = PedidoDetalle::where('vendedor_id', $user->id)
                        ->where('estado_solicitud', 'pendiente')
                        ->count();
                }
            }

            $view->with($counts);
        });
    }
}
