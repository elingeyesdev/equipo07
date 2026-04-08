<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Rendimiento de Vendedores</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }
        .subtitulo {
            font-size: 12px;
            color: #555;
            margin-bottom: 15px;
        }
        .kpi-box {
            border: 1px solid #cccccc;
            border-radius: 4px;
            padding: 6px 8px;
            margin-bottom: 8px;
        }
        .kpi-label {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #cccccc;
            padding: 4px 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tfoot td {
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .small { font-size: 9px; color: #777; }
    </style>
</head>
<body>
    <h1>Reporte de Rendimiento de Vendedores</h1>
    <div class="subtitulo">
        Período:
        {{ $desde->format('d/m/Y') }} a {{ $hasta->format('d/m/Y') }}
    </div>

    <div class="kpi-box">
        <div><span class="kpi-label">Total vendedores:</span> {{ $totalVendedores }}</div>
        <div><span class="kpi-label">Total productos publicados:</span> {{ $totalProductos }}</div>
        <div><span class="kpi-label">Ventas totales:</span> Bs. {{ number_format($ventasTotales, 2, ',', '.') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Vendedor</th>
                <th>Email</th>
                <th class="text-center">Total productos</th>
                <th class="text-center">Animales</th>
                <th class="text-center">Maquinaria</th>
                <th class="text-center">Orgánicos</th>
                <th class="text-right">Ventas totales (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rendimientoVendedores as $index => $v)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $v['nombre'] }}</td>
                    <td>{{ $v['email'] }}</td>
                    <td class="text-center">{{ $v['total_productos'] }}</td>
                    <td class="text-center">{{ $v['total_ganados'] }}</td>
                    <td class="text-center">{{ $v['total_maquinarias'] }}</td>
                    <td class="text-center">{{ $v['total_organicos'] }}</td>
                    <td class="text-right">Bs. {{ number_format($v['total_ventas'], 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay datos para el período seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="small">
        Generado el {{ now()->format('d/m/Y H:i') }} - Mercado Agrícola
    </p>
</body>
</html>
