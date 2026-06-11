<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganicoController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\GanadoController;
use App\Http\Controllers\TipoAnimalController;
use App\Http\Controllers\TipoCultivoController;
use App\Http\Controllers\TipoPesoController;
use App\Http\Controllers\CertificadoOrganicoController;
use App\Http\Controllers\DatoSanitarioController;
use App\Http\Controllers\RazaController;
use App\Http\Controllers\PropositoController;
use App\Http\Controllers\EstadoMaquinariaController;
use App\Http\Controllers\SolicitudVendedorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\AdminPedidoController;
use App\Http\Controllers\AdminTransportistaController;
use App\Http\Controllers\PedidoUbicacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TransportistaEnvioController;
use App\Http\Controllers\VendedorSolicitudController;
use App\Http\Controllers\TransportePublicoController;
use App\Http\Controllers\ProductoVentaController;
use App\Http\Controllers\InteraccionOrganicoController;
use App\Http\Controllers\ReclamoController;
use App\Http\Controllers\VendedorTransportistaController;


Route::view('/', 'public.landing')->name('landing');

Route::get('/transporte', [TransportePublicoController::class, 'index'])
    ->name('transporte.index');
Route::post('/transporte/acceder', [TransportePublicoController::class, 'acceder'])
    ->middleware('throttle:8,1')
    ->name('transporte.acceder');
Route::middleware('transporte.externo')->group(function () {
    Route::get('/transporte/envio', [TransportePublicoController::class, 'envio'])
        ->name('transporte.envio');
    Route::post('/transporte/envio/ubicacion', [TransportePublicoController::class, 'ubicacion'])
        ->middleware('throttle:120,1')
        ->name('transporte.ubicacion');
    Route::post('/transporte/envio/estado', [TransportePublicoController::class, 'estado'])
        ->middleware('throttle:20,1')
        ->name('transporte.estado');
    Route::get('/transporte/envio/actualizacion', [TransportePublicoController::class, 'actualizacion'])
        ->middleware('throttle:60,1')
        ->name('transporte.actualizacion');
    Route::post('/transporte/salir', [TransportePublicoController::class, 'salir'])
        ->name('transporte.salir');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/registro', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/registro', [RegisterController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware('auth')->get('/pedidos/detalles/{detalle}/estado-transporte', [PedidoUbicacionController::class, 'estadoDetalle'])->name('pedidos.detalles.estadoTransporte');
Route::get('/inicio', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/anuncios', [App\Http\Controllers\HomeController::class, 'anuncios'])->name('ads.index');
Route::view('/publicar', 'public.ads.create')->middleware('not.transportista')->name('ads.create');

// RUTAS POR ROLES

// ADMINISTRADOR 
Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/detalle-json', [AdminDashboardController::class, 'detalleJson'])->name('dashboard.detalleJson');
    Route::patch('/reclamos/{reclamo}', [ReclamoController::class, 'actualizarEstado'])->name('reclamos.update');

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
    Route::resource('propositos', PropositoController::class);
    Route::resource('tipo_maquinarias', App\Http\Controllers\TipoMaquinariaController::class);
    Route::resource('marcas_maquinarias', App\Http\Controllers\MarcaMaquinariaController::class);
    Route::resource('estado_maquinarias', EstadoMaquinariaController::class);
    Route::resource('unidades_organicos', App\Http\Controllers\UnidadOrganicoController::class);
    Route::resource('tipo_cultivos', TipoCultivoController::class)->except(['show', 'create', 'edit']);
    Route::resource('certificados_organicos', CertificadoOrganicoController::class)->except(['show', 'create', 'edit']);
    Route::get('/organicos/certificados-pendientes', [OrganicoController::class, 'certificadosPendientes'])
        ->name('organicos.certificados.pendientes');
    Route::patch('/organicos/certificados/{certificado}/estado', [OrganicoController::class, 'actualizarEstadoCertificado'])
        ->name('organicos.certificados.estado');


    Route::get('/pedidos', [AdminPedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedido}', [AdminPedidoController::class, 'show'])->name('pedidos.show');
    Route::put('/pedidos/{pedido}/estado', [AdminPedidoController::class, 'updateEstado'])->name('pedidos.updateEstado');

    Route::get('/transportistas', [AdminTransportistaController::class, 'index'])->name('transportistas.index');

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
    Route::get('/productos-en-venta', [ProductoVentaController::class, 'index'])->name('productos-venta.index');

    Route::resource('ganados', GanadoController::class)->except(['index', 'show']);
    Route::resource('maquinarias', MaquinariaController::class)->except(['index', 'show'])->names('maquinarias');
    Route::resource('organicos', OrganicoController::class)->except(['index', 'show'])->names('organicos');

    Route::get('/vendedor/solicitudes', [VendedorSolicitudController::class, 'index'])->name('vendedor.solicitudes.index');
    Route::get('/vendedor/solicitudes/{solicitud}', [VendedorSolicitudController::class, 'show'])->name('vendedor.solicitudes.show');
    Route::post('/vendedor/solicitudes/{solicitud}/aceptar', [VendedorSolicitudController::class, 'aceptar'])->name('vendedor.solicitudes.aceptar');
    Route::post('/vendedor/solicitudes/{solicitud}/cancelar', [VendedorSolicitudController::class, 'cancelar'])->name('vendedor.solicitudes.cancelar');
    Route::post('/vendedor/solicitudes/{solicitud}/alquiler/avanzar', [VendedorSolicitudController::class, 'avanzarAlquiler'])->name('vendedor.solicitudes.alquiler.avanzar');
    Route::post('/vendedor/solicitudes/{solicitud}/finalizar', [VendedorSolicitudController::class, 'finalizarPedido'])->name('vendedor.solicitudes.finalizar');
    Route::post('/vendedor/solicitudes/{solicitud}/transportista', [VendedorSolicitudController::class, 'asignarTransportista'])->name('vendedor.solicitudes.transportista.asignar');
    Route::post('/vendedor/solicitudes/{solicitud}/transporte/codigo', [VendedorSolicitudController::class, 'regenerarCodigo'])
        ->name('vendedor.solicitudes.transporte.codigo');
    Route::delete('/vendedor/solicitudes/{solicitud}/transporte/codigo', [VendedorSolicitudController::class, 'revocarCodigo'])
        ->name('vendedor.solicitudes.transporte.revocar');
    Route::post('/vendedor/solicitudes/{solicitud}/transporte/preparado', [VendedorSolicitudController::class, 'marcarPreparado'])
        ->name('vendedor.solicitudes.transporte.preparado');

    Route::get('/vendedor/transportistas', [VendedorTransportistaController::class, 'index'])->name('vendedor.transportistas.index');
    Route::post('/vendedor/transportistas', [VendedorTransportistaController::class, 'store'])->name('vendedor.transportistas.store');
});

Route::middleware('auth')->get(
    '/pedidos/detalles/{detalle}/tracking-actual',
    [PedidoUbicacionController::class, 'detalleLatest']
)->name('pedidos.detalles.tracking.latest');

Route::middleware('auth')->get(
    '/mis-pedidos/{pedido}/estados-actuales',
    [PedidoUbicacionController::class, 'estadosPedido']
)->name('pedidos.tracking.estados');

Route::middleware(['auth', 'role.transportista'])->group(function () {
    Route::get('/transportista/envios', [TransportistaEnvioController::class, 'index'])->name('transportista.envios.index');
    Route::get('/transportista/envios/historial', [TransportistaEnvioController::class, 'historial'])->name('transportista.envios.historial');
    Route::get('/transportista/envios/{envio}', [TransportistaEnvioController::class, 'show'])->name('transportista.envios.show');
    Route::get('/transportista/envios/{envio}/tracking', [TransportistaEnvioController::class, 'tracking'])->name('transportista.envios.tracking');
    Route::post('/transportista/envios/{solicitud}/tracking', [PedidoUbicacionController::class, 'store'])->name('transportista.envios.tracking.store');
    Route::post('/transportista/envios/{solicitud}/tracking/estado', [PedidoUbicacionController::class, 'avanzarEstado'])->name('transportista.envios.tracking.estado');
});

// TODOS LOS USUARIOS AUTENTICADOS
Route::middleware(['auth', 'not.transportista'])->group(function () {
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
    Route::get('/mis-pedidos/historial', [PedidoController::class, 'historial'])->name('pedidos.historial');
    Route::get('/mis-pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('/mis-pedidos/{pedido}/ubicacion-actual', [PedidoUbicacionController::class, 'latest'])->name('pedidos.tracking.latest');
    Route::post('/mis-pedidos/detalles/{detalle}/confirmar-recepcion', [PedidoController::class, 'confirmarRecepcion'])->name('pedidos.detalles.confirmarRecepcion');
    Route::post('/pedidos/detalles/{detalle}/resena', [InteraccionOrganicoController::class, 'guardarResena'])
        ->name('resenas.store');
    Route::post('/pedidos/detalles/{detalle}/reclamo', [InteraccionOrganicoController::class, 'guardarReclamo'])
        ->name('reclamos.store');
    Route::get('/reclamos', [ReclamoController::class, 'index'])->name('reclamos.index');
    Route::get('/reclamos/{reclamo}', [ReclamoController::class, 'show'])->name('reclamos.show');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
});

// CLIENTE
Route::middleware(['auth', 'role.cliente'])->group(function () {
    Route::get('/solicitar-vendedor', [SolicitudVendedorController::class, 'create'])->name('solicitar-vendedor');
    Route::post('/solicitar-vendedor', [SolicitudVendedorController::class, 'store'])->name('solicitar-vendedor.store');
});
