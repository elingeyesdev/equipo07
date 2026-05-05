@extends('layouts.adminlte')

@section('title', 'Categorías del Sistema')

@section('content')
    <style>
        .categories-card {
            border-radius: 15px;
            overflow: hidden;
            border: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }
        .categories-header {
            background: var(--agro);
            color: #fff;
            padding: 1.5rem 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .categories-header h2 {
            font-size: 1.4rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .categories-body {
            background: #fff;
            padding: 1.5rem 1.75rem 1.25rem;
        }
        .table-categories thead th {
            border-top: 0;
            font-size: .9rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #6c757d;
            background-color: #f8f9fa;
        }
        .badge-locked {
            background-color: #ffeeba;
            color: #856404;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
    </style>

    <div class="container-fluid">
        
        <!-- Alerta de Arquitectura -->
        <div class="alert alert-warning border-warning shadow-sm" role="alert">
            <h5 class="font-weight-bold mb-1"><i class="fas fa-lock mr-2"></i> Pilares del Sistema (Solo Lectura)</h5>
            <p class="mb-0">Las categorías mostradas aquí representan los pilares estructurales de la plataforma (Ganado, Maquinaria, etc.). Su creación o eliminación está bloqueada por seguridad, ya que cada una requiere interfaces de usuario y bases de datos programadas a medida.</p>
        </div>

        <div class="categories-card card">
            <div class="categories-header">
                <h2>
                    <i class="fas fa-project-diagram"></i>
                    Estructura Base
                </h2>
                <!-- El botón de "Nueva Categoría" fue eliminado por seguridad -->
            </div>

            <div class="categories-body">
                <div class="table-responsive">
                    <table class="table table-hover table-categories mb-0">
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th style="width: 200px;">Pilar de Negocio</th>
                                <th>Descripción</th>
                                <th class="text-center" style="width: 150px;">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categorias as $categoria)
                                <tr>
                                    <td class="font-weight-bold text-muted">#{{ $categoria->id }}</td>
                                    <td class="font-weight-bold text-dark">{{ $categoria->nombre }}</td>
                                    <td class="text-muted">{{ $categoria->descripcion }}</td>
                                    <td class="text-center">
                                        <span class="badge-locked"><i class="fas fa-shield-alt"></i> Protegido</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Error crítico: No se detectaron categorías base en la base de datos.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection