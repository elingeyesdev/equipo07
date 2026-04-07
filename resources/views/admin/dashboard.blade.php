@extends('layouts.adminlte')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1 text-dark">
                    <i class="fas fa-chart-line text-success"></i> Dashboard del Mercado Agrícola
                </h1>
                <p class="text-muted mb-0">
                    Resumen general de animales, maquinaria y productos orgánicos publicados en la plataforma.
                </p>
                <small class="text-muted">
                    @if (request('desde') || request('hasta') || request('tipo'))
                        Vista filtrada
                        @if (request('desde'))
                            desde <strong>{{ request('desde') }}</strong>
                        @endif
                        @if (request('hasta'))
                            hasta <strong>{{ request('hasta') }}</strong>
                        @endif
                        @if (request('tipo'))
                            @php
                                $labelTipo =
                                    [
                                        'ganado' => 'Animales',
                                        'maquinaria' => 'Maquinaria',
                                        'organico' => 'Orgánicos',
                                    ][request('tipo')] ?? 'Todos';
                            @endphp
                            · Tipo: <strong>{{ $labelTipo }}</strong>
                        @endif
                    @else
                        Mostrando información global del sistema (todos los tipos y fechas).
                    @endif
                </small>
            </div>

            <div class="text-right">
                <span class="badge badge-success">
                    <i class="fas fa-circle mr-1"></i> Sistema operativo
                </span>
                @isset($ultimaActualizacion)
                    <div class="small text-muted mt-1">
                        Última actualización: {{ $ultimaActualizacion }}
                    </div>
                @endisset
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body py-2">
                <form method="GET">
                    <div class="form-row align-items-end">
                        <div class="col-md-3 col-sm-6 mb-2">
                            <label class="small mb-1">Desde</label>
                            <input type="date" name="desde" value="{{ request('desde') }}"
                                class="form-control form-control-sm">
                        </div>

                        <div class="col-md-3 col-sm-6 mb-2">
                            <label class="small mb-1">Hasta</label>
                            <input type="date" name="hasta" value="{{ request('hasta') }}"
                                class="form-control form-control-sm">
                        </div>

                        <div class="col-md-3 col-sm-6 mb-2">
                            <label class="small mb-1">Tipo de publicación</label>
                            <select name="tipo" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                <option value="ganado" {{ request('tipo') == 'ganado' ? 'selected' : '' }}>Animales</option>
                                <option value="maquinaria" {{ request('tipo') == 'maquinaria' ? 'selected' : '' }}>
                                    Maquinaria</option>
                                <option value="organico" {{ request('tipo') == 'organico' ? 'selected' : '' }}>Orgánicos
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-2 d-flex">
                            <button class="btn btn-success btn-sm mr-2 flex-fill">
                                <i class="fas fa-filter mr-1"></i> Aplicar filtros
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-undo mr-1"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @php
            $tipoFiltro = $tipo ?? request('tipo');
        @endphp

        <div class="row">
            @if (!$tipoFiltro || $tipoFiltro === 'ganado')
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalGanado }}</h3>
                            <p>Animales publicados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-horse"></i>
                        </div>
                        <a href="{{ route('ganados.index') }}" class="small-box-footer">
                            Ver todos los animales <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif

            @if (!$tipoFiltro || $tipoFiltro === 'maquinaria')
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalMaquinaria }}</h3>
                            <p>Maquinaria publicada</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tractor"></i>
                        </div>
                        <a href="{{ route('maquinarias.index') }}" class="small-box-footer">
                            Ver toda la maquinaria <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif

            @if (!$tipoFiltro || $tipoFiltro === 'organico')
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalOrganicos }}</h3>
                            <p>Productos orgánicos publicados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-carrot"></i>
                        </div>
                        <a href="{{ route('organicos.index') }}" class="small-box-footer text-dark">
                            Ver todos los orgánicos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $totalPublicaciones }}</h3>
                        <p>Total de publicaciones</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <span class="small-box-footer">
                        Mercado general
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm border-left-success h-100 kpi-click" data-kpi="hoy" style="cursor:pointer;">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-calendar-day text-success mr-1"></i> Publicaciones de hoy
                        </h6>
                        <h3 class="mb-0">{{ $totalHoy }}</h3>
                        <small class="text-muted">Animales, maquinaria y orgánicos creados hoy.</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm border-left-info h-100 kpi-click" data-kpi="semana" style="cursor:pointer;">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-calendar-week text-info mr-1"></i> Últimos 7 días
                        </h6>
                        <h3 class="mb-0">{{ $totalSemana }}</h3>
                        <small class="text-muted d-block mb-1">Nuevas publicaciones en la última semana.</small>
                        @isset($variacionSemanaPorcentaje)
                            <span class="badge badge-{{ $variacionSemanaPorcentaje >= 0 ? 'success' : 'danger' }}">
                                <i class="fas fa-arrow-{{ $variacionSemanaPorcentaje >= 0 ? 'up' : 'down' }} mr-1"></i>
                                {{ number_format($variacionSemanaPorcentaje, 1) }}% vs semana anterior
                            </span>
                        @endisset
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm border-left-warning h-100 kpi-click" data-kpi="mes" style="cursor:pointer;">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-calendar-alt text-warning mr-1"></i> Mes actual
                        </h6>
                        <h3 class="mb-0">{{ $totalMes }}</h3>
                        <small class="text-muted d-block mb-1">Publicaciones registradas en el mes en curso.</small>
                        @isset($variacionMesPorcentaje)
                            <span class="badge badge-{{ $variacionMesPorcentaje >= 0 ? 'success' : 'danger' }}">
                                <i class="fas fa-arrow-{{ $variacionMesPorcentaje >= 0 ? 'up' : 'down' }} mr-1"></i>
                                {{ number_format($variacionMesPorcentaje, 1) }}% vs mes anterior
                            </span>
                        @endisset
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm border-left-primary h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-balance-scale text-primary mr-1"></i> Promedio diario (mes)
                        </h6>
                        @isset($promedioPublicacionesDiaMes)
                            <h3 class="mb-0">{{ number_format($promedioPublicacionesDiaMes, 1) }}</h3>
                        @else
                            <h3 class="mb-0">—</h3>
                        @endisset
                        <small class="text-muted">Promedio de publicaciones por día en el mes actual.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            @if (!$tipoFiltro || $tipoFiltro === 'ganado')
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Participación de Animales</h6>
                                <h3 class="mb-0">{{ $porcentajeGanado }}%</h3>
                                <small class="text-muted">Sobre el total de publicaciones.</small>
                            </div>
                            <i class="fas fa-horse fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            @endif

            @if (!$tipoFiltro || $tipoFiltro === 'maquinaria')
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Participación de Maquinaria</h6>
                                <h3 class="mb-0">{{ $porcentajeMaquinaria }}%</h3>
                                <small class="text-muted">Sobre el total de publicaciones.</small>
                            </div>
                            <i class="fas fa-tractor fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            @endif

            @if (!$tipoFiltro || $tipoFiltro === 'organico')
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Participación de Orgánicos</h6>
                                <h3 class="mb-0">{{ $porcentajeOrganicos }}%</h3>
                                <small class="text-muted">Sobre el total de publicaciones.</small>
                            </div>
                            <i class="fas fa-leaf fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm h-100 kpi-click" data-kpi="vendedores" style="cursor:pointer;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Vendedores activos</h6>
                            @isset($totalVendedoresActivos)
                                <h3 class="mb-0">{{ $totalVendedoresActivos }}</h3>
                            @else
                                <h3 class="mb-0">—</h3>
                            @endisset
                            <small class="text-muted">Usuarios con publicaciones recientes.</small>
                        </div>
                        <i class="fas fa-user-check fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0 bg-light d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-chart-bar text-success mr-1"></i> Publicaciones por categoría
                        </h3>
                        <small class="text-muted">Distribución actual por tipo.</small>
                    </div>
                    <div class="card-body">
                        <div style="height: 260px;">
                            <canvas id="graficoCategorias"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0 bg-light d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-chart-area text-success mr-1"></i> Evolución últimos 6 meses
                        </h3>
                        <small class="text-muted">Tendencia por tipo de publicación.</small>
                    </div>
                    <div class="card-body">
                        <div style="height: 260px;">
                            <canvas id="graficoMeses"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @if (!$tipoFiltro || $tipoFiltro === 'ganado')
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header border-0 d-flex align-items-center bg-light">
                            <i class="fas fa-horse text-success mr-2"></i>
                            <h3 class="card-title mb-0">Animales</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">
                                Estadísticas y publicaciones de ganado disponible en el mercado.
                            </p>
                            <ul class="small mb-3">
                                <li>Control de animales registrados.</li>
                                <li>Acceso rápido al listado completo.</li>
                            </ul>
                            <a href="{{ route('ganados.index') }}" class="btn btn-sm btn-success">
                                Ir a animales
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if (!$tipoFiltro || $tipoFiltro === 'maquinaria')
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header border-0 d-flex align-items-center bg-light">
                            <i class="fas fa-tractor text-info mr-2"></i>
                            <h3 class="card-title mb-0">Maquinaria</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">
                                Seguimiento de la oferta de maquinaria agrícola disponible.
                            </p>
                            <ul class="small mb-3">
                                <li>Resumen de equipos publicados.</li>
                                <li>Monitoreo de oferta por tipo.</li>
                            </ul>
                            <a href="{{ route('maquinarias.index') }}" class="btn btn-sm btn-info">
                                Ir a maquinaria
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if (!$tipoFiltro || $tipoFiltro === 'organico')
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header border-0 d-flex align-items-center bg-light">
                            <i class="fas fa-leaf text-warning mr-2"></i>
                            <h3 class="card-title mb-0">Productos orgánicos</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">
                                Visualiza rápidamente la cantidad de productos orgánicos disponibles.
                            </p>
                            <ul class="small mb-3">
                                <li>Control de productos registrados.</li>
                                <li>Acceso rápido a listado y detalles.</li>
                            </ul>
                            <a href="{{ route('organicos.index') }}" class="btn btn-sm btn-warning text-white">
                                Ir a orgánicos
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctxCategorias = document.getElementById('graficoCategorias').getContext('2d');

            var labelsCat = [];
            var dataCat = [];
            var bgCat = [];
            var borderCat = [];

            @if (!$tipoFiltro || $tipoFiltro === 'ganado')
                labelsCat.push('Animales');
                dataCat.push({{ $totalGanado }});
                bgCat.push('rgba(40, 167, 69, 0.7)');
                borderCat.push('rgba(40, 167, 69, 1)');
            @endif

            @if (!$tipoFiltro || $tipoFiltro === 'maquinaria')
                labelsCat.push('Maquinaria');
                dataCat.push({{ $totalMaquinaria }});
                bgCat.push('rgba(23, 162, 184, 0.7)');
                borderCat.push('rgba(23, 162, 184, 1)');
            @endif

            @if (!$tipoFiltro || $tipoFiltro === 'organico')
                labelsCat.push('Orgánicos');
                dataCat.push({{ $totalOrganicos }});
                bgCat.push('rgba(255, 193, 7, 0.7)');
                borderCat.push('rgba(255, 193, 7, 1)');
            @endif

            new Chart(ctxCategorias, {
                type: 'bar',
                data: {
                    labels: labelsCat,
                    datasets: [{
                        label: 'Publicaciones registradas',
                        data: dataCat,
                        backgroundColor: bgCat,
                        borderColor: borderCat,
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' publicación(es)';
                                }
                            }
                        }
                    }
                }
            });

            var ctxMeses = document.getElementById('graficoMeses').getContext('2d');

            var datasetsMeses = [];

            @if (!$tipoFiltro || $tipoFiltro === 'ganado')
                datasetsMeses.push({
                    label: 'Animales',
                    data: {!! json_encode($ganadoPorMes) !!},
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                });
            @endif

            @if (!$tipoFiltro || $tipoFiltro === 'maquinaria')
                datasetsMeses.push({
                    label: 'Maquinaria',
                    data: {!! json_encode($maquinariaPorMes) !!},
                    borderColor: 'rgba(23, 162, 184, 1)',
                    backgroundColor: 'rgba(23, 162, 184, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                });
            @endif

            @if (!$tipoFiltro || $tipoFiltro === 'organico')
                datasetsMeses.push({
                    label: 'Orgánicos',
                    data: {!! json_encode($organicosPorMes) !!},
                    borderColor: 'rgba(255, 193, 7, 1)',
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                });
            @endif

            new Chart(ctxMeses, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labelsMeses) !!},
                    datasets: datasetsMeses
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y +
                                        ' publicaciones';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

    <!-- MODAL DETALLE KPI -->
    <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo">Detalle</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light" id="theadDetalle">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Publicación</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="tablaDetalle">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Cargando información...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            document.querySelectorAll('.kpi-click').forEach(card => {
                card.addEventListener('click', async () => {

                    const kpi = card.dataset.kpi || '';
                    const tipo = document.querySelector('select[name="tipo"]')?.value || '';
                    const desde = document.querySelector('input[name="desde"]')?.value || '';
                    const hasta = document.querySelector('input[name="hasta"]')?.value || '';

                    const params = new URLSearchParams({
                        kpi,
                        tipo,
                        desde,
                        hasta
                    }).toString();
                    const url = `/admin/dashboard/detalle-json?${params}`;

                    document.getElementById('modalTitulo').innerText =
                        kpi === 'hoy' ? 'Detalle: Publicaciones de hoy' :
                        kpi === 'semana' ? 'Detalle: Últimos 7 días' :
                        kpi === 'mes' ? 'Detalle: Mes actual' :
                        'Detalle: Vendedores activos';

                    document.getElementById('tablaDetalle').innerHTML =
                        `<tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>`;

                    $('#modalDetalle').modal('show');

                    try {
                        console.log('FETCH URL:', url);

                        const res = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const raw = await res.text();
                        console.log('RAW RESPONSE:', raw);

                        let data = [];
                        try {
                            data = JSON.parse(raw);
                        } catch (e) {
                            data = null;
                        }

                        if (data && data.modo === 'usuarios') {

                            document.getElementById('theadDetalle').innerHTML = `
                        <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Total (30d)</th>
                        <th>Desglose</th>
                        </tr>
                    `;

                            const rows = data.data || [];
                            document.getElementById('tablaDetalle').innerHTML = rows.length ?
                                rows.map(u => `
                            <tr>
                            <td>${u.usuario}</td>
                            <td>${u.email}</td>
                            <td>${u.total}</td>
                            <td>
                                Animales: ${u.animales} · Maquinaria: ${u.maquinaria} · Orgánicos: ${u.organicos}
                            </td>
                            </tr>
                        `).join('') :
                                `<tr><td colspan="4" class="text-center text-muted">Sin vendedores activos</td></tr>`;

                            return;
                        }

                        if (!Array.isArray(data)) {
                            document.getElementById('tablaDetalle').innerHTML =
                                `<tr><td colspan="4" class="text-danger">No llegó JSON válido.</td></tr>`;
                            return;
                        }

                        document.getElementById('theadDetalle').innerHTML = `
                    <tr>
                        <th>Tipo</th>
                        <th>Publicación</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                    </tr>
                    `;

                        document.getElementById('tablaDetalle').innerHTML = data.length ?
                            data.map(x => `
                        <tr>
                            <td>${x.tipo ?? '—'}</td>
                            <td>${x.titulo ?? '—'}</td>
                            <td>${x.usuario ?? '—'}</td>
                            <td>${x.fecha ?? '—'}</td>
                        </tr>
                        `).join('') :
                            `<tr><td colspan="4" class="text-center text-muted">Sin datos</td></tr>`;


                        document.getElementById('tablaDetalle').innerHTML = data.length ?
                            data.map(x => `
              <tr>
                <td>${x.tipo ?? '—'}</td>
                <td>${x.titulo ?? '—'}</td>
                <td>${x.usuario ?? '—'}</td>
                <td>${x.fecha ?? '—'}</td>
              </tr>
            `).join('') :
                            `<tr><td colspan="4" class="text-center text-muted">Sin datos</td></tr>`;

                    } catch (err) {
                        console.error(err);
                        document.getElementById('tablaDetalle').innerHTML =
                            `<tr><td colspan="4" class="text-center text-danger">Error en fetch (mira consola)</td></tr>`;
                    }
                });
            });

        });
    </script>




@endsection
