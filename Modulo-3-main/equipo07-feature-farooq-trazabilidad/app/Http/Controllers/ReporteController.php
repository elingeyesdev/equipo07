<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\User;
use App\Models\TipoCultivo;
use App\Exports\VentasExport;
use App\Exports\VendedoresExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosLentosExport;
use Barryvdh\DomPDF\Facade\Pdf;




class ReporteController extends Controller
{
    /**
     * Reporte de Análisis de Ventas y Rentabilidad
     */
    public function ventas(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->subMonths(6);
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();
        $categoria = $request->categoria; 
        $estado = $request->estado; 
        $vendedor_id = $request->vendedor_id;

        $pedidosQuery = Pedido::with(['user', 'detalles'])
            ->whereBetween('created_at', [$desde, $hasta]);

        if ($estado) {
            $pedidosQuery->where('estado', $estado);
        }

        if ($vendedor_id) {
            $pedidosQuery->where('user_id', $vendedor_id);
        }

        $pedidos = $pedidosQuery->get();

        $ventasPorCategoria = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->whereBetween('pedidos.created_at', [$desde, $hasta])
            ->when($estado, function ($q) use ($estado) {
                return $q->where('pedidos.estado', $estado);
            })
            ->when($vendedor_id, function ($q) use ($vendedor_id) {
                return $q->where('pedidos.user_id', $vendedor_id);
            })
            ->when($categoria, function ($q) use ($categoria) {
                return $q->where('pedido_detalles.product_type', $categoria);
            })
            ->select(
                'pedido_detalles.product_type as categoria',
                DB::raw('COUNT(DISTINCT pedidos.id) as total_pedidos'),
                DB::raw('SUM(pedido_detalles.cantidad) as total_cantidad'),
                DB::raw('SUM(pedido_detalles.subtotal) as ingresos_totales'),
                DB::raw('AVG(pedido_detalles.precio_unitario) as precio_promedio')
            )
            ->groupBy('pedido_detalles.product_type')
            ->get();

        $labelsMeses = [];
        $ventasGanado = [];
        $ventasMaquinaria = [];
        $ventasOrganico = [];

        for ($i = 5; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $mesInicio = $fecha->copy()->startOfMonth();
            $mesFin = $fecha->copy()->endOfMonth();

            $labelsMeses[] = $fecha->translatedFormat('M Y');

            $ventasGanado[] = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->where('pedido_detalles.product_type', 'ganado')
                ->whereBetween('pedidos.created_at', [$mesInicio, $mesFin])
                ->when($estado, fn($q) => $q->where('pedidos.estado', $estado))
                ->when($vendedor_id, fn($q) => $q->where('pedidos.user_id', $vendedor_id))
                ->sum('pedido_detalles.subtotal');

            $ventasMaquinaria[] = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->where('pedido_detalles.product_type', 'maquinaria')
                ->whereBetween('pedidos.created_at', [$mesInicio, $mesFin])
                ->when($estado, fn($q) => $q->where('pedidos.estado', $estado))
                ->when($vendedor_id, fn($q) => $q->where('pedidos.user_id', $vendedor_id))
                ->sum('pedido_detalles.subtotal');

            $ventasOrganico[] = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->where('pedido_detalles.product_type', 'organico')
                ->whereBetween('pedidos.created_at', [$mesInicio, $mesFin])
                ->when($estado, fn($q) => $q->where('pedidos.estado', $estado))
                ->when($vendedor_id, fn($q) => $q->where('pedidos.user_id', $vendedor_id))
                ->sum('pedido_detalles.subtotal');
        }

        $ventasPorVendedor = [];

        $ventasGanado = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->join('ganados', 'pedido_detalles.product_id', '=', 'ganados.id')
            ->where('pedido_detalles.product_type', 'ganado')
            ->whereBetween('pedidos.created_at', [$desde, $hasta])
            ->when($estado, fn($q) => $q->where('pedidos.estado', $estado))
            ->select(
                'ganados.user_id as vendedor_id',
                DB::raw('COUNT(DISTINCT pedidos.id) as total_pedidos'),
                DB::raw('SUM(pedido_detalles.subtotal) as ingresos_totales'),
                DB::raw('SUM(pedido_detalles.cantidad) as total_cantidad')
            )
            ->groupBy('ganados.user_id')
            ->get();

        foreach ($ventasGanado as $venta) {
            $vendedorId = $venta->vendedor_id;
            if (!isset($ventasPorVendedor[$vendedorId])) {
                $ventasPorVendedor[$vendedorId] = [
                    'total_pedidos' => 0,
                    'ingresos_totales' => 0,
                    'total_cantidad' => 0,
                ];
            }
            $ventasPorVendedor[$vendedorId]['total_pedidos'] += $venta->total_pedidos;
            $ventasPorVendedor[$vendedorId]['ingresos_totales'] += $venta->ingresos_totales;
            $ventasPorVendedor[$vendedorId]['total_cantidad'] += $venta->total_cantidad;
        }

        $ventasMaquinaria = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->join('maquinarias', 'pedido_detalles.product_id', '=', 'maquinarias.id')
            ->where('pedido_detalles.product_type', 'maquinaria')
            ->whereBetween('pedidos.created_at', [$desde, $hasta])
            ->when($estado, fn($q) => $q->where('pedidos.estado', $estado))
            ->select(
                'maquinarias.user_id as vendedor_id',
                DB::raw('COUNT(DISTINCT pedidos.id) as total_pedidos'),
                DB::raw('SUM(pedido_detalles.subtotal) as ingresos_totales'),
                DB::raw('SUM(pedido_detalles.cantidad) as total_cantidad')
            )
            ->groupBy('maquinarias.user_id')
            ->get();

        foreach ($ventasMaquinaria as $venta) {
            $vendedorId = $venta->vendedor_id;
            if (!isset($ventasPorVendedor[$vendedorId])) {
                $ventasPorVendedor[$vendedorId] = [
                    'total_pedidos' => 0,
                    'ingresos_totales' => 0,
                    'total_cantidad' => 0,
                ];
            }
            $ventasPorVendedor[$vendedorId]['total_pedidos'] += $venta->total_pedidos;
            $ventasPorVendedor[$vendedorId]['ingresos_totales'] += $venta->ingresos_totales;
            $ventasPorVendedor[$vendedorId]['total_cantidad'] += $venta->total_cantidad;
        }

        $ventasOrganico = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->join('organicos', 'pedido_detalles.product_id', '=', 'organicos.id')
            ->where('pedido_detalles.product_type', 'organico')
            ->whereBetween('pedidos.created_at', [$desde, $hasta])
            ->when($estado, fn($q) => $q->where('pedidos.estado', $estado))
            ->select(
                'organicos.user_id as vendedor_id',
                DB::raw('COUNT(DISTINCT pedidos.id) as total_pedidos'),
                DB::raw('SUM(pedido_detalles.subtotal) as ingresos_totales'),
                DB::raw('SUM(pedido_detalles.cantidad) as total_cantidad')
            )
            ->groupBy('organicos.user_id')
            ->get();

        foreach ($ventasOrganico as $venta) {
            $vendedorId = $venta->vendedor_id;
            if (!isset($ventasPorVendedor[$vendedorId])) {
                $ventasPorVendedor[$vendedorId] = [
                    'total_pedidos' => 0,
                    'ingresos_totales' => 0,
                    'total_cantidad' => 0,
                ];
            }
            $ventasPorVendedor[$vendedorId]['total_pedidos'] += $venta->total_pedidos;
            $ventasPorVendedor[$vendedorId]['ingresos_totales'] += $venta->ingresos_totales;
            $ventasPorVendedor[$vendedorId]['total_cantidad'] += $venta->total_cantidad;
        }

        $topVendedores = collect($ventasPorVendedor)
            ->map(function ($ventas, $vendedorId) {
                $user = User::find($vendedorId);
                $ticketPromedio = $ventas['total_pedidos'] > 0
                    ? $ventas['ingresos_totales'] / $ventas['total_pedidos']
                    : 0;

                return [
                    'vendedor_id' => $vendedorId,
                    'nombre' => $user ? $user->name : 'Usuario eliminado',
                    'email' => $user ? $user->email : '-',
                    'total_pedidos' => $ventas['total_pedidos'],
                    'ingresos_totales' => $ventas['ingresos_totales'],
                    'total_cantidad' => $ventas['total_cantidad'],
                    'ticket_promedio' => round($ticketPromedio, 2),
                ];
            })
            ->sortByDesc('ingresos_totales')
            ->take(10)
            ->values();

        $productosMasVendidos = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->whereBetween('pedidos.created_at', [$desde, $hasta])
            ->when($estado, fn($q) => $q->where('pedidos.estado', $estado))
            ->when($vendedor_id, fn($q) => $q->where('pedidos.user_id', $vendedor_id))
            ->when($categoria, fn($q) => $q->where('pedido_detalles.product_type', $categoria))
            ->select(
                'pedido_detalles.product_type',
                'pedido_detalles.nombre_producto',
                DB::raw('SUM(pedido_detalles.cantidad) as total_vendido'),
                DB::raw('SUM(pedido_detalles.subtotal) as ingresos'),
                DB::raw('AVG(pedido_detalles.precio_unitario) as precio_promedio')
            )
            ->groupBy('pedido_detalles.product_type', 'pedido_detalles.nombre_producto')
            ->orderBy('total_vendido', 'desc')
            ->limit(15)
            ->get();

        $totalPedidos = $pedidos->count();
        $totalIngresos = $pedidos->sum('total');
        $ticketPromedio = $totalPedidos > 0 ? round($totalIngresos / $totalPedidos, 2) : 0;

        $pedidosTotalesQuery = Pedido::whereBetween('created_at', [$desde, $hasta]);
        if ($vendedor_id) {
            $pedidosTotalesQuery->where('user_id', $vendedor_id);
        }
        $pedidosTotales = $pedidosTotalesQuery->get();
        $totalPedidosParaConversion = $pedidosTotales->count();
        $pedidosEntregados = $pedidosTotales->where('estado', 'entregado')->count();
        $tasaConversion = $totalPedidosParaConversion > 0 ? round(($pedidosEntregados / $totalPedidosParaConversion) * 100, 2) : 0;

        $distribucionEstado = $pedidos->groupBy('estado')->map(function ($grupo) {
            return $grupo->count();
        });

        $vendedoresIds = collect();

        $ganadosVendidos = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->where('pedido_detalles.product_type', 'ganado')
            ->whereBetween('pedidos.created_at', [$desde, $hasta])
            ->pluck('pedido_detalles.product_id')
            ->unique();
        $vendedoresGanado = \App\Models\Ganado::whereIn('id', $ganadosVendidos)->pluck('user_id')->unique();

        $maquinariasVendidas = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->where('pedido_detalles.product_type', 'maquinaria')
            ->whereBetween('pedidos.created_at', [$desde, $hasta])
            ->pluck('pedido_detalles.product_id')
            ->unique();
        $vendedoresMaquinaria = \App\Models\Maquinaria::whereIn('id', $maquinariasVendidas)->pluck('user_id')->unique();

        $organicosVendidos = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->where('pedido_detalles.product_type', 'organico')
            ->whereBetween('pedidos.created_at', [$desde, $hasta])
            ->pluck('pedido_detalles.product_id')
            ->unique();
        $vendedoresOrganico = \App\Models\Organico::whereIn('id', $organicosVendidos)->pluck('user_id')->unique();

        $vendedoresIds = $vendedoresIds->merge($vendedoresGanado)
            ->merge($vendedoresMaquinaria)
            ->merge($vendedoresOrganico)
            ->unique();

        $vendedores = User::whereIn('id', $vendedoresIds)->get(['id', 'name', 'email']);

        return view('admin.reportes.ventas', compact(
            'ventasPorCategoria',
            'labelsMeses',
            'ventasGanado',
            'ventasMaquinaria',
            'ventasOrganico',
            'topVendedores',
            'productosMasVendidos',
            'totalPedidos',
            'totalIngresos',
            'ticketPromedio',
            'tasaConversion',
            'distribucionEstado',
            'vendedores',
            'desde',
            'hasta',
            'categoria',
            'estado',
            'vendedor_id'
        ));
    }

    /**
     * Exportar reporte de ventas a Excel
     */
    public function exportarVentasExcel(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->subMonths(6);
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();
        $categoria = $request->categoria;
        $estado = $request->estado;
        $vendedor_id = $request->vendedor_id;

        $ventasPorCategoria = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->whereBetween('pedidos.created_at', [$desde, $hasta])
            ->when($estado, function ($q) use ($estado) {
                return $q->where('pedidos.estado', $estado);
            })
            ->when($vendedor_id, function ($q) use ($vendedor_id) {
                return $q->where('pedidos.user_id', $vendedor_id);
            })
            ->when($categoria, function ($q) use ($categoria) {
                return $q->where('pedido_detalles.product_type', $categoria);
            })
            ->select(
                'pedido_detalles.product_type as categoria',
                DB::raw('COUNT(DISTINCT pedidos.id) as total_pedidos'),
                DB::raw('SUM(pedido_detalles.cantidad) as total_cantidad'),
                DB::raw('SUM(pedido_detalles.subtotal) as ingresos_totales'),
                DB::raw('AVG(pedido_detalles.precio_unitario) as precio_promedio')
            )
            ->groupBy('pedido_detalles.product_type')
            ->get();

        $export = new VentasExport($ventasPorCategoria);
        return $export->export();
    }

    /**
     * Exportar reporte de ventas a PDF
     */
    public function exportarVentasPdf(Request $request)
    {
        $desde       = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->subMonths(6);
        $hasta       = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();
        $categoria   = $request->categoria;
        $estado      = $request->estado;
        $vendedor_id = $request->vendedor_id;

        $pedidosQuery = Pedido::whereBetween('created_at', [$desde, $hasta]);

        if ($estado) {
            $pedidosQuery->where('estado', $estado);
        }
        if ($vendedor_id) {
            $pedidosQuery->where('user_id', $vendedor_id);
        }

        $pedidos        = $pedidosQuery->get();
        $totalPedidos   = $pedidos->count();
        $totalIngresos  = $pedidos->sum('total');
        $ticketPromedio = $totalPedidos > 0 ? round($totalIngresos / $totalPedidos, 2) : 0;

        $ventasPorCategoria = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
            ->whereBetween('pedidos.created_at', [$desde, $hasta])
            ->when($estado, function ($q) use ($estado) {
                return $q->where('pedidos.estado', $estado);
            })
            ->when($vendedor_id, function ($q) use ($vendedor_id) {
                return $q->where('pedidos.user_id', $vendedor_id);
            })
            ->when($categoria, function ($q) use ($categoria) {
                return $q->where('pedido_detalles.product_type', $categoria);
            })
            ->select(
                'pedido_detalles.product_type as categoria',
                DB::raw('COUNT(DISTINCT pedidos.id) as total_pedidos'),
                DB::raw('SUM(pedido_detalles.cantidad) as total_cantidad'),
                DB::raw('SUM(pedido_detalles.subtotal) as ingresos_totales'),
                DB::raw('AVG(pedido_detalles.precio_unitario) as precio_promedio')
            )
            ->groupBy('pedido_detalles.product_type')
            ->get();

        $pedidosTotalesQuery = Pedido::whereBetween('created_at', [$desde, $hasta]);
        if ($vendedor_id) {
            $pedidosTotalesQuery->where('user_id', $vendedor_id);
        }
        $pedidosTotales   = $pedidosTotalesQuery->get();
        $totalParaConv    = $pedidosTotales->count();
        $pedidosEntregados = $pedidosTotales->where('estado', 'entregado')->count();
        $tasaConversion   = $totalParaConv > 0 ? round(($pedidosEntregados / $totalParaConv) * 100, 2) : 0;

        $pdf = Pdf::loadView('admin.reportes.ventas_pdf', [
            'desde'              => $desde,
            'hasta'              => $hasta,
            'categoria'          => $categoria,
            'estado'             => $estado,
            'vendedor_id'        => $vendedor_id,
            'ventasPorCategoria' => $ventasPorCategoria,
            'totalPedidos'       => $totalPedidos,
            'totalIngresos'      => $totalIngresos,
            'ticketPromedio'     => $ticketPromedio,
            'tasaConversion'     => $tasaConversion,
        ]);

        $fechaArchivo = Carbon::now()->format('Y-m-d_His');

        return $pdf->download('reporte_ventas_' . $fechaArchivo . '.pdf');
    }



    /**
     * Reporte de Rendimiento de Vendedores
     */
    public function vendedores(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->subMonths(6);
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();
        $vendedor_id = $request->vendedor_id;

        $vendedoresQuery = User::whereHas('ganados')
            ->orWhereHas('maquinarias')
            ->orWhereHas('organicos');

        if ($vendedor_id) {
            $vendedoresQuery->where('id', $vendedor_id);
        }

        $vendedores = $vendedoresQuery->get();

        $rendimientoVendedores = [];

        foreach ($vendedores as $vendedor) {
            $totalGanados = $vendedor->ganados()->count();
            $totalMaquinarias = $vendedor->maquinarias()->count();
            $totalOrganicos = $vendedor->organicos()->count();
            $totalProductos = $totalGanados + $totalMaquinarias + $totalOrganicos;

            $ventasGanado = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->join('ganados', 'pedido_detalles.product_id', '=', 'ganados.id')
                ->where('pedido_detalles.product_type', 'ganado')
                ->where('ganados.user_id', $vendedor->id)
                ->whereBetween('pedidos.created_at', [$desde, $hasta])
                ->sum('pedido_detalles.subtotal');

            $ventasMaquinaria = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->join('maquinarias', 'pedido_detalles.product_id', '=', 'maquinarias.id')
                ->where('pedido_detalles.product_type', 'maquinaria')
                ->where('maquinarias.user_id', $vendedor->id)
                ->whereBetween('pedidos.created_at', [$desde, $hasta])
                ->sum('pedido_detalles.subtotal');

            $ventasOrganico = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->join('organicos', 'pedido_detalles.product_id', '=', 'organicos.id')
                ->where('pedido_detalles.product_type', 'organico')
                ->where('organicos.user_id', $vendedor->id)
                ->whereBetween('pedidos.created_at', [$desde, $hasta])
                ->sum('pedido_detalles.subtotal');

            $totalVentas = $ventasGanado + $ventasMaquinaria + $ventasOrganico;

            $tasaConversion = $totalProductos > 0
                ? round((($totalGanados > 0 && $ventasGanado > 0 ? 1 : 0) +
                    ($totalMaquinarias > 0 && $ventasMaquinaria > 0 ? 1 : 0) +
                    ($totalOrganicos > 0 && $ventasOrganico > 0 ? 1 : 0)) / 3 * 100, 2)
                : 0;

            $preciosGanado = $vendedor->ganados()->avg('precio') ?? 0;
            $preciosMaquinaria = $vendedor->maquinarias()->avg('precio_dia') ?? 0;
            $preciosOrganico = $vendedor->organicos()->avg('precio') ?? 0;
            $precioPromedio = ($preciosGanado + $preciosMaquinaria + $preciosOrganico) / 3;

            $rendimientoVendedores[] = [
                'id' => $vendedor->id,
                'nombre' => $vendedor->name,
                'email' => $vendedor->email,
                'total_productos' => $totalProductos,
                'total_ganados' => $totalGanados,
                'total_maquinarias' => $totalMaquinarias,
                'total_organicos' => $totalOrganicos,
                'total_ventas' => $totalVentas,
                'ventas_ganado' => $ventasGanado,
                'ventas_maquinaria' => $ventasMaquinaria,
                'ventas_organico' => $ventasOrganico,
                'tasa_conversion' => $tasaConversion,
                'precio_promedio' => round($precioPromedio, 2),
            ];
        }

        usort($rendimientoVendedores, function ($a, $b) {
            return $b['total_ventas'] <=> $a['total_ventas'];
        });

        $promedioVentas = count($rendimientoVendedores) > 0
            ? array_sum(array_column($rendimientoVendedores, 'total_ventas')) / count($rendimientoVendedores)
            : 0;

        $topVendedores = array_slice($rendimientoVendedores, 0, 5);
        $restoVendedores = array_slice($rendimientoVendedores, 5);

        return view('admin.reportes.vendedores', compact(
            'rendimientoVendedores',
            'topVendedores',
            'restoVendedores',
            'promedioVentas',
            'vendedores',
            'desde',
            'hasta',
            'vendedor_id'
        ));
    }

    /**
     * Exportar reporte de vendedores a Excel
     */
    public function exportarVendedoresExcel(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->subMonths(6);
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();
        $vendedor_id = $request->vendedor_id;

        $vendedoresQuery = User::whereHas('ganados')
            ->orWhereHas('maquinarias')
            ->orWhereHas('organicos');

        if ($vendedor_id) {
            $vendedoresQuery->where('id', $vendedor_id);
        }

        $vendedores = $vendedoresQuery->get();
        $rendimientoVendedores = [];

        foreach ($vendedores as $vendedor) {
            $totalGanados = $vendedor->ganados()->count();
            $totalMaquinarias = $vendedor->maquinarias()->count();
            $totalOrganicos = $vendedor->organicos()->count();
            $totalProductos = $totalGanados + $totalMaquinarias + $totalOrganicos;

            $ventasGanado = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->join('ganados', 'pedido_detalles.product_id', '=', 'ganados.id')
                ->where('pedido_detalles.product_type', 'ganado')
                ->where('ganados.user_id', $vendedor->id)
                ->whereBetween('pedidos.created_at', [$desde, $hasta])
                ->sum('pedido_detalles.subtotal');

            $ventasMaquinaria = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->join('maquinarias', 'pedido_detalles.product_id', '=', 'maquinarias.id')
                ->where('pedido_detalles.product_type', 'maquinaria')
                ->where('maquinarias.user_id', $vendedor->id)
                ->whereBetween('pedidos.created_at', [$desde, $hasta])
                ->sum('pedido_detalles.subtotal');

            $ventasOrganico = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->join('organicos', 'pedido_detalles.product_id', '=', 'organicos.id')
                ->where('pedido_detalles.product_type', 'organico')
                ->where('organicos.user_id', $vendedor->id)
                ->whereBetween('pedidos.created_at', [$desde, $hasta])
                ->sum('pedido_detalles.subtotal');

            $totalVentas = $ventasGanado + $ventasMaquinaria + $ventasOrganico;

            $rendimientoVendedores[] = [
                'nombre' => $vendedor->name,
                'email' => $vendedor->email,
                'total_productos' => $totalProductos,
                'total_ganados' => $totalGanados,
                'total_maquinarias' => $totalMaquinarias,
                'total_organicos' => $totalOrganicos,
                'total_ventas' => $totalVentas,
            ];
        }

        usort($rendimientoVendedores, function ($a, $b) {
            return $b['total_ventas'] <=> $a['total_ventas'];
        });

        $export = new VendedoresExport($rendimientoVendedores);
        return $export->export();
    }

    /**
     * Exportar reporte de vendedores a PDF
     */
    public function exportarVendedoresPdf(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->subMonths(6);
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();
        $vendedor_id = $request->vendedor_id;

        $vendedoresQuery = User::whereHas('ganados')
            ->orWhereHas('maquinarias')
            ->orWhereHas('organicos');

        if ($vendedor_id) {
            $vendedoresQuery->where('id', $vendedor_id);
        }

        $vendedores = $vendedoresQuery->get();
        $rendimientoVendedores = [];

        foreach ($vendedores as $vendedor) {
            $totalGanados     = $vendedor->ganados()->count();
            $totalMaquinarias = $vendedor->maquinarias()->count();
            $totalOrganicos   = $vendedor->organicos()->count();
            $totalProductos   = $totalGanados + $totalMaquinarias + $totalOrganicos;

            $ventasGanado = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->join('ganados', 'pedido_detalles.product_id', '=', 'ganados.id')
                ->where('pedido_detalles.product_type', 'ganado')
                ->where('ganados.user_id', $vendedor->id)
                ->whereBetween('pedidos.created_at', [$desde, $hasta])
                ->sum('pedido_detalles.subtotal');

            $ventasMaquinaria = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->join('maquinarias', 'pedido_detalles.product_id', '=', 'maquinarias.id')
                ->where('pedido_detalles.product_type', 'maquinaria')
                ->where('maquinarias.user_id', $vendedor->id)
                ->whereBetween('pedidos.created_at', [$desde, $hasta])
                ->sum('pedido_detalles.subtotal');

            $ventasOrganico = PedidoDetalle::join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->join('organicos', 'pedido_detalles.product_id', '=', 'organicos.id')
                ->where('pedido_detalles.product_type', 'organico')
                ->where('organicos.user_id', $vendedor->id)
                ->whereBetween('pedidos.created_at', [$desde, $hasta])
                ->sum('pedido_detalles.subtotal');

            $totalVentas = $ventasGanado + $ventasMaquinaria + $ventasOrganico;

            $rendimientoVendedores[] = [
                'nombre'            => $vendedor->name,
                'email'             => $vendedor->email,
                'total_productos'   => $totalProductos,
                'total_ganados'     => $totalGanados,
                'total_maquinarias' => $totalMaquinarias,
                'total_organicos'   => $totalOrganicos,
                'total_ventas'      => $totalVentas,
            ];
        }

        // ordenar por ventas desc
        usort($rendimientoVendedores, function ($a, $b) {
            return $b['total_ventas'] <=> $a['total_ventas'];
        });

        $totalVendedores = count($rendimientoVendedores);
        $totalProductos  = array_sum(array_column($rendimientoVendedores, 'total_productos'));
        $ventasTotales   = array_sum(array_column($rendimientoVendedores, 'total_ventas'));

        // Generar PDF con el facade Pdf
        $pdf = Pdf::loadView('admin.reportes.vendedores_pdf', [
            'desde'                 => $desde,
            'hasta'                 => $hasta,
            'vendedor_id'           => $vendedor_id,
            'rendimientoVendedores' => $rendimientoVendedores,
            'totalVendedores'       => $totalVendedores,
            'totalProductos'        => $totalProductos,
            'ventasTotales'         => $ventasTotales,
        ]);

        $fechaArchivo = Carbon::now()->format('Y-m-d_His');

        return $pdf->download('reporte_vendedores_' . $fechaArchivo . '.pdf');
    }

    public function pedidosPorCliente(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->subMonths(1);
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();

        $estado = $request->estado ?? 'todos';
        $clienteId = $request->cliente_id ?? 'todos';

        $clientes = User::orderBy('name')->get(['id', 'name', 'email']);

        $query = Pedido::query()
            ->join('users', 'users.id', '=', 'pedidos.user_id')
            ->select('pedidos.*', 'users.name as cliente_nombre', 'users.email as cliente_email')
            ->whereBetween('pedidos.created_at', [$desde, $hasta]);

        if ($estado !== 'todos') {
            $query->where('pedidos.estado', $estado);
        }

        if ($clienteId !== 'todos') {
            $query->where('pedidos.user_id', $clienteId);
        }

        $pedidos = $query->orderBy('pedidos.created_at', 'desc')->paginate(10);

        $totalPedidos   = (clone $query)->count();
        $montoTotal     = (clone $query)->sum('pedidos.total');
        $clientesUnicos = (clone $query)->distinct('pedidos.user_id')->count('pedidos.user_id');

        return view('admin.reportes.pedidos_clientes', compact(
            'pedidos',
            'clientes',
            'desde',
            'hasta',
            'estado',
            'clienteId',
            'totalPedidos',
            'montoTotal',
            'clientesUnicos'
        ));
    }

    public function exportarPedidosClientesPdf(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->subMonths(1);
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();

        $estado = $request->estado ?? 'todos';
        $clienteId = $request->cliente_id ?? 'todos';

        $query = Pedido::query()
            ->join('users', 'users.id', '=', 'pedidos.user_id')
            ->select('pedidos.*', 'users.name as cliente_nombre', 'users.email as cliente_email')
            ->whereBetween('pedidos.created_at', [$desde, $hasta]);

        if ($estado !== 'todos') {
            $query->where('pedidos.estado', $estado);
        }

        if ($clienteId !== 'todos') {
            $query->where('pedidos.user_id', $clienteId);
        }

        $pedidos = $query->orderBy('pedidos.created_at', 'desc')->get();

        $totalPedidos   = $pedidos->count();
        $montoTotal     = $pedidos->sum('total');
        $clientesUnicos = $pedidos->pluck('user_id')->unique()->count();

        $pdf = Pdf::loadView('admin.reportes.pedidos_clientes_pdf', compact(
            'pedidos',
            'desde',
            'hasta',
            'estado',
            'clienteId',
            'totalPedidos',
            'montoTotal',
            'clientesUnicos'
        ));

        $fechaArchivo = Carbon::now()->format('Y-m-d_His');
        return $pdf->download('reporte_pedidos_clientes_' . $fechaArchivo . '.pdf');
    }

    /**
     * Reporte de Productos con Bajo Movimiento / Estancados
     */
    public function reporteProductosLentos(Request $request)
    {
        $desde     = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->subMonths(6);
        $hasta     = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();
        $categoria = $request->categoria ?? 'todas';   
        $minVentas = $request->min_ventas !== null ? (int) $request->min_ventas : 1;

        $animales = DB::table('ganados')
            ->join('users', 'users.id', '=', 'ganados.user_id')
            ->select(
                'ganados.id as product_id',
                DB::raw("'ganado' as product_type"),
                DB::raw("'Animales' as categoria"),
                'ganados.nombre as producto',
                'users.name as vendedor',
                'ganados.precio as precio'
            );

        $maquinarias = DB::table('maquinarias')
            ->join('users', 'users.id', '=', 'maquinarias.user_id')
            ->select(
                'maquinarias.id as product_id',
                DB::raw("'maquinaria' as product_type"),
                DB::raw("'Maquinaria' as categoria"),
                'maquinarias.nombre as producto',
                'users.name as vendedor',
                'maquinarias.precio_dia as precio'
            );

        $organicos = DB::table('organicos')
            ->join('users', 'users.id', '=', 'organicos.user_id')
            ->select(
                'organicos.id as product_id',
                DB::raw("'organico' as product_type"),
                DB::raw("'Orgánicos' as categoria"),
                'organicos.nombre as producto',
                'users.name as vendedor',
                'organicos.precio as precio'
            );

        $productosBase = $animales
            ->unionAll($maquinarias)
            ->unionAll($organicos);

        $query = DB::query()
            ->fromSub($productosBase, 'p')
            ->leftJoin('pedido_detalles as d', function ($join) {
                $join->on('d.product_id', '=', 'p.product_id')
                    ->on('d.product_type', '=', 'p.product_type');
            })
            ->leftJoin('pedidos as pe', 'pe.id', '=', 'd.pedido_id')
            ->select(
                'p.categoria',
                'p.producto',
                'p.vendedor',
                'p.precio',
                DB::raw('COALESCE(SUM(d.cantidad), 0) as total_vendido'),
                DB::raw('COALESCE(SUM(d.subtotal), 0) as ingresos'),
                DB::raw('MAX(pe.created_at) as ultima_venta')
            )
            ->when($desde, function ($q) use ($desde) {
                return $q->where(function ($q) use ($desde) {
                    $q->whereNull('pe.created_at')
                        ->orWhereDate('pe.created_at', '>=', $desde);
                });
            })
            ->when($hasta, function ($q) use ($hasta) {
                return $q->where(function ($q) use ($hasta) {
                    $q->whereNull('pe.created_at')
                        ->orWhereDate('pe.created_at', '<=', $hasta);
                });
            })
            ->when($categoria && $categoria !== 'todas', function ($q) use ($categoria) {
                return $q->where('p.categoria', $categoria);
            })
            ->groupBy('p.categoria', 'p.producto', 'p.vendedor', 'p.precio')
            ->havingRaw('COALESCE(SUM(d.cantidad), 0) <= ?', [$minVentas])
            ->orderBy('total_vendido')
            ->orderBy('ultima_venta', 'asc');


        $productos = $query->get();

        $totalProductos = $productos->count();
        $sinVentas      = $productos->where('total_vendido', 0)->count();
        $conPocasVentas = $totalProductos - $sinVentas;

        return view('admin.reportes.productos_lentos', compact(
            'productos',
            'desde',
            'hasta',
            'categoria',
            'minVentas',
            'totalProductos',
            'sinVentas',
            'conPocasVentas'
        ));
    }

    /**
     * Exportar reporte de productos lentos
     */
    public function exportProductosLentos(Request $request, $tipo)
    {
        $desde     = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->subMonths(6);
        $hasta     = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();
        $categoria = $request->categoria ?? 'todas';
        $minVentas = $request->min_ventas !== null ? (int) $request->min_ventas : 1;

        $animales = DB::table('ganados')
            ->join('users', 'users.id', '=', 'ganados.user_id')
            ->select(
                'ganados.id as product_id',
                DB::raw("'ganado' as product_type"),
                DB::raw("'Animales' as categoria"),
                'ganados.nombre as producto',
                'users.name as vendedor',
                'ganados.precio as precio'
            );

        $maquinarias = DB::table('maquinarias')
            ->join('users', 'users.id', '=', 'maquinarias.user_id')
            ->select(
                'maquinarias.id as product_id',
                DB::raw("'maquinaria' as product_type"),
                DB::raw("'Maquinaria' as categoria"),
                'maquinarias.nombre as producto',
                'users.name as vendedor',
                'maquinarias.precio_dia' . ' as precio'
            );

        $organicos = DB::table('organicos')
            ->join('users', 'users.id', '=', 'organicos.user_id')
            ->select(
                'organicos.id as product_id',
                DB::raw("'organico' as product_type"),
                DB::raw("'Orgánicos' as categoria"),
                'organicos.nombre as producto',
                'users.name as vendedor',
                'organicos.precio as precio'
            );

        $productosBase = $animales
            ->unionAll($maquinarias)
            ->unionAll($organicos);

        $query = DB::query()
            ->fromSub($productosBase, 'p')
            ->leftJoin('pedido_detalles as d', function ($join) {
                $join->on('d.product_id', '=', 'p.product_id')
                    ->on('d.product_type', '=', 'p.product_type');
            })
            ->leftJoin('pedidos as pe', 'pe.id', '=', 'd.pedido_id')
            ->select(
                'p.categoria',
                'p.producto',
                'p.vendedor',
                'p.precio',
                DB::raw('COALESCE(SUM(d.cantidad), 0) as total_vendido'),
                DB::raw('COALESCE(SUM(d.subtotal), 0) as ingresos'),
                DB::raw('MAX(pe.created_at) as ultima_venta')
            )
            ->when($desde, fn($q) => $q->where(function ($q) use ($desde) {
                $q->whereNull('pe.created_at')->orWhereDate('pe.created_at', '>=', $desde);
            }))
            ->when($hasta, fn($q) => $q->where(function ($q) use ($hasta) {
                $q->whereNull('pe.created_at')->orWhereDate('pe.created_at', '<=', $hasta);
            }))
            ->when($categoria && $categoria !== 'todas', fn($q) => $q->where('p.categoria', $categoria))
            ->groupBy('p.categoria', 'p.producto', 'p.vendedor', 'p.precio')
            ->havingRaw('COALESCE(SUM(d.cantidad), 0) <= ?', [$minVentas])
            ->orderBy('total_vendido')
            ->orderBy('ultima_venta', 'asc');

        $productos = $query->get();

        if ($tipo === 'excel') {
            $export = new ProductosLentosExport($productos);
            return $export->export();
        }

        if ($tipo === 'pdf') {
            $fechaArchivo = Carbon::now()->format('Y-m-d_His');

            $pdf = Pdf::loadView('admin.reportes.productos_lentos_pdf', [
                'productos'       => $productos,
                'desde'           => $desde,
                'hasta'           => $hasta,
                'categoria'       => $categoria,
                'minVentas'       => $minVentas,
                'totalProductos'  => $productos->count(),
                'sinVentas'       => $productos->where('total_vendido', 0)->count(),
                'conPocasVentas'  => $productos->where('total_vendido', '>', 0)->count(),
            ]);

            return $pdf->download('reporte_productos_lentos_' . $fechaArchivo . '.pdf');
        }


        abort(404);
    }
}
