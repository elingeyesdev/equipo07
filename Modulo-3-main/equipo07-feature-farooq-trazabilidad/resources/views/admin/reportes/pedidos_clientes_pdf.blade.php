<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Reporte de Pedidos por Cliente</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #555;
            padding: 4px 6px;
        }

        th {
            background: #e0e0e0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Reporte de Pedidos por Cliente</h1>

    <p>
        Periodo:
        <strong>{{ $desde->format('d/m/Y') }}</strong>
        al
        <strong>{{ $hasta->format('d/m/Y') }}</strong><br>

        Estado:
        <strong>{{ $estado === 'todos' ? 'Todos' : strtoupper(str_replace('_', ' ', $estado)) }}</strong><br>

        Cliente:
        <strong>
            @if ($clienteId === 'todos')
                Todos
            @else
                {{ $pedidos->first()->cliente_nombre ?? 'Cliente' }}
            @endif
        </strong>
    </p>

    <p>
        Total pedidos: <strong>{{ $totalPedidos }}</strong><br>
        Monto total: <strong>Bs {{ number_format($montoTotal, 2) }}</strong><br>
        Clientes únicos: <strong>{{ $clientesUnicos }}</strong>
    </p>

    <table>
        <thead>
            <tr>
                <th># Pedido</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Email</th>
                <th class="text-center">Estado</th>
                <th class="text-right">Total (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $p->cliente_nombre }}</td>
                    <td>{{ $p->cliente_email }}</td>
                    <td class="text-center">{{ strtoupper($p->estado) }}</td>
                    <td class="text-right">{{ number_format($p->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        No se encontraron pedidos para los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="small">
        Generado el {{ now()->format('d/m/Y H:i') }} - Mercado Agrícola
    </p>
</body>

</html>
