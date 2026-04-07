<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos con Bajo Movimiento</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 4px;
        }
        th {
            background: #f2f2f2;
        }
        h3 {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h3>Reporte de Productos con Bajo Movimiento</h3>
    <p>
        Periodo:
        @if($desde) {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} @else - @endif
        a
        @if($hasta) {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }} @else - @endif
        | Máx. ventas: {{ $minVentas }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Producto</th>
                <th>Vendedor</th>
                <th>Precio</th>
                <th>Total vendido</th>
                <th>Ingresos</th>
                <th>Última venta</th>
            </tr>
        </thead>
        <tbody>
        @foreach($productos as $p)
            <tr>
                <td>{{ $p->categoria }}</td>
                <td>{{ $p->producto }}</td>
                <td>{{ $p->vendedor }}</td>
                <td>{{ number_format($p->precio, 2) }}</td>
                <td>{{ $p->total_vendido }}</td>
                <td>{{ number_format($p->ingresos, 2) }}</td>
                <td>{{ $p->ultima_venta ? \Carbon\Carbon::parse($p->ultima_venta)->format('d/m/Y') : 'Sin ventas' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
