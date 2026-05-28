<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganicoController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\GanadoController;
use App\Http\Controllers\TipoAnimalController;
use App\Http\Controllers\TipoPesoController;
use App\Http\Controllers\DatoSanitarioController;
use App\Http\Controllers\RazaController;
use App\Http\Controllers\EstadoMaquinariaController;
use App\Http\Controllers\SolicitudVendedorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\AdminPedidoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\VendedorSolicitudController;


Route::get('/', function () {
    $formatPrice = function ($value, string $suffix = '') {
        if ($value === null || $value === '') {
            return 'Consultar precio';
        }

        $amount = number_format((float) $value, 2, ',', '.');
        $amount = rtrim(rtrim($amount, '0'), ',');

        return 'Bs. ' . $amount . $suffix;
    };

    $formatLocation = function (?string $departamento, ?string $municipio, ?string $fallback = null) {
        $parts = array_filter([$departamento, $municipio]);

        return $parts ? implode(' - ', $parts) : ($fallback ?: 'Bolivia');
    };

    $sellerInitials = function ($user) {
        $name = trim((string) ($user->name ?? 'Vendedor'));
        $words = preg_split('/\s+/', $name) ?: [];

        return strtoupper(collect($words)->take(2)->map(fn ($word) => mb_substr($word, 0, 1))->implode('')) ?: 'AV';
    };

    $landingProducts = \App\Models\Ganado::with([
            'user',
            'imagenes',
            'datoComercial',
            'ubicacionGanado.ubicacionGeografica',
        ])
        ->latest()
        ->take(3)
        ->get()
        ->map(fn ($ganado) => [
            'tone' => 'green',
            'tag' => 'Ganado',
            'loc' => $formatLocation($ganado->departamento, $ganado->municipio, $ganado->ubicacion),
            'title' => $ganado->nombre,
            'price' => $formatPrice($ganado->precio),
            'seller' => $sellerInitials($ganado->user),
            'image' => optional($ganado->imagenes->first())->ruta,
            'icon' => 'fa-horse',
            'created_at' => $ganado->created_at,
        ])
        ->concat(
            \App\Models\Maquinaria::with([
                    'user',
                    'imagenes',
                    'ubicacionMaquinaria.ubicacionGeografica',
                ])
                ->latest()
                ->take(3)
                ->get()
                ->map(fn ($maquinaria) => [
                    'tone' => '',
                    'tag' => 'Maquinaria',
                    'loc' => $formatLocation($maquinaria->departamento, $maquinaria->municipio, $maquinaria->ubicacion),
                    'title' => $maquinaria->nombre,
                    'price' => $formatPrice($maquinaria->precio_dia, $maquinaria->tarifa_unidad ? '/' . $maquinaria->tarifa_unidad : ''),
                    'seller' => $sellerInitials($maquinaria->user),
                    'image' => optional($maquinaria->imagenes->first())->ruta,
                    'icon' => 'fa-tractor',
                    'created_at' => $maquinaria->created_at,
                ])
        )
        ->concat(
            \App\Models\Organico::with([
                    'user',
                    'imagenes',
                    'datoComercial',
                    'unidadOrganico',
                    'ubicacionOrganico.ubicacionGeografica',
                ])
                ->latest()
                ->take(3)
                ->get()
                ->map(fn ($organico) => [
                    'tone' => 'green',
                    'tag' => 'Orgánico',
                    'loc' => $formatLocation($organico->departamento_origen, $organico->municipio_origen, $organico->origen),
                    'title' => $organico->nombre,
                    'price' => $formatPrice($organico->precio, $organico->unidadOrganico?->nombre ? '/' . $organico->unidadOrganico->nombre : ''),
                    'seller' => $sellerInitials($organico->user),
                    'image' => optional($organico->imagenes->first())->ruta,
                    'icon' => 'fa-leaf',
                    'created_at' => $organico->created_at,
                ])
        )
        ->sortByDesc('created_at')
        ->take(3)
        ->values();

    return view('public.landing', compact('landingProducts'));
})->name('landing');
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/registro', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/registro', [RegisterController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/inicio', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/anuncios', [App\Http\Controllers\HomeController::class, 'anuncios'])->name('ads.index');
Route::view('/publicar', 'public.ads.create')->name('ads.create');

// RUTAS POR ROLES

// ADMINISTRADOR 
Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/detalle-json', [AdminDashboardController::class, 'detalleJson'])->name('dashboard.detalleJson');

    // Gestión de solicitudes de vendedor
    Route::get('/solicitudes-vendedor', [SolicitudVendedorController::class, 'index'])->name('solicitudes-vendedor.index');
    Route::get('/solicitudes-vendedor/{solicitudVendedor}', [SolicitudVendedorController::class, 'show'])->name('solicitudes-vendedor.show');
    Route::post('/solicitudes-vendedor/{id}/aprobar', [SolicitudVendedorController::class, 'aprobar'])->name('solicitudes-vendedor.aprobar');
    Route::post('/solicitudes-vendedor/{id}/rechazar', [SolicitudVendedorController::class, 'rechazar'])->name('solicitudes-vendedor.rechazar');

    // Parámetros del sistema
    Route::resource('categorias', App\Http\Controllers\CategoriaController::class);
    Route::resource('tipo_animals', TipoAnimalController::class);
    Route::resource('tipo-pesos', TipoPesoController::class);
    Route::resource('razas', RazaController::class);
    Route::resource('tipo_maquinarias', App\Http\Controllers\TipoMaquinariaController::class);
    Route::resource('marcas_maquinarias', App\Http\Controllers\MarcaMaquinariaController::class);
    Route::resource('estado_maquinarias', EstadoMaquinariaController::class);
    Route::resource('unidades_organicos', App\Http\Controllers\UnidadOrganicoController::class);
    Route::patch('/organicos/certificados/{certificado}/estado', [OrganicoController::class, 'actualizarEstadoCertificado'])
        ->name('organicos.certificados.estado');


    Route::get('/pedidos', [AdminPedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedido}', [AdminPedidoController::class, 'show'])->name('pedidos.show');
    Route::put('/pedidos/{pedido}/estado', [AdminPedidoController::class, 'updateEstado'])->name('pedidos.updateEstado');

    // REPORTES
    // Ventas
    Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
    Route::get('/reportes/ventas/exportar-excel', [ReporteController::class, 'exportarVentasExcel'])->name('reportes.ventas.excel');
    Route::get('/reportes/ventas/exportar-pdf', [ReporteController::class, 'exportarVentasPdf'])->name('reportes.ventas.export.pdf');

    // Vendedores
    Route::get('/reportes/vendedores',[ReporteController::class, 'vendedores'])->name('reportes.vendedores');

    Route::get('/reportes/vendedores/exportar-excel',[ReporteController::class, 'exportarVendedoresExcel'])->name('reportes.vendedores.excel');

    Route::get('/reportes/vendedores/exportar-pdf',[ReporteController::class, 'exportarVendedoresPdf'])->name('reportes.vendedores.export.pdf');

    // Productos con bajo movimiento
    Route::get('/productos-lentos',[ReporteController::class, 'reporteProductosLentos'])->name('productos_lentos');

    Route::get('/productos-lentos/export/{tipo}',[ReporteController::class, 'exportProductosLentos'])->name('productos_lentos.export');

    // PEDIDOS POR CLIENTE
    Route::get('/reportes/pedidos-clientes',[ReporteController::class, 'pedidosPorCliente'])->name('reportes.pedidos_clientes');
    Route::get('/reportes/pedidos-clientes/exportar-pdf',[ReporteController::class, 'exportarPedidosClientesPdf'])->name('reportes.pedidos_clientes.export.pdf');
});

// VENDEDOR Y ADMINISTRADOR
// Datos sanitarios
Route::middleware(['auth', 'role.vendedor'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('datos-sanitarios', DatoSanitarioController::class);
});

Route::middleware(['auth', 'role.vendedor'])->group(function () {
    Route::resource('ganados', GanadoController::class)->except(['index', 'show']);
    Route::resource('maquinarias', MaquinariaController::class)->except(['index', 'show'])->names('maquinarias');
    Route::resource('organicos', OrganicoController::class)->except(['index', 'show'])->names('organicos');

    Route::get('/vendedor/solicitudes', [VendedorSolicitudController::class, 'index'])->name('vendedor.solicitudes.index');
    Route::get('/vendedor/solicitudes/{solicitud}', [VendedorSolicitudController::class, 'show'])->name('vendedor.solicitudes.show');
    Route::post('/vendedor/solicitudes/{solicitud}/aceptar', [VendedorSolicitudController::class, 'aceptar'])->name('vendedor.solicitudes.aceptar');
    Route::post('/vendedor/solicitudes/{solicitud}/cancelar', [VendedorSolicitudController::class, 'cancelar'])->name('vendedor.solicitudes.cancelar');
});

// TODOS LOS USUARIOS AUTENTICADOS
Route::middleware('auth')->group(function () {
    Route::get('ganados', [GanadoController::class, 'index'])->name('ganados.index');
    Route::get('ganados/{ganado}', [GanadoController::class, 'show'])->name('ganados.show');
    Route::get('maquinarias', [MaquinariaController::class, 'index'])->name('maquinarias.index');
    Route::get('maquinarias/{maquinaria}', [MaquinariaController::class, 'show'])->name('maquinarias.show');
    Route::get('organicos', [OrganicoController::class, 'index'])->name('organicos.index');
    Route::get('organicos/{organico}', [OrganicoController::class, 'show'])->name('organicos.show');

    // Carrito de compras
    Route::get('carrito', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('carrito/agregar', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('carrito/{cartItem}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('carrito/{cartItem}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('carrito', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::get('carrito/count', [App\Http\Controllers\CartController::class, 'getCount'])->name('cart.count');

    Route::get('/api/geocodificacion', [GanadoController::class, 'obtenerGeocodificacion'])->name('api.geocodificacion');

    Route::get('/mis-pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/mis-pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
});

// CLIENTE
Route::middleware(['auth', 'role.cliente'])->group(function () {
    Route::get('/solicitar-vendedor', [SolicitudVendedorController::class, 'create'])->name('solicitar-vendedor');
    Route::post('/solicitar-vendedor', [SolicitudVendedorController::class, 'store'])->name('solicitar-vendedor.store');
});
