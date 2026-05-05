@extends('layouts.adminlte')
@section('title', 'Especies (Tipos de Animales)')

@section('content')
<!-- Mismos estilos de tu header ... -->
<style>
    .types-card { border-radius: 15px; overflow: hidden; border: 0; box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
    .types-header { background: var(--agro); color: #fff; padding: 1.5rem 1.75rem; display: flex; justify-content: space-between; align-items: center; }
    .types-body { background: #fff; padding: 1.5rem 1.75rem 1.25rem; }
    .table-types thead th { border-top: 0; font-size: .9rem; text-transform: uppercase; color: #6c757d; background-color: #f8f9fa; }
    .badge-soft { padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
</style>

<div class="container-fluid">
    <div class="types-card card">
        <div class="types-header">
            <h2 class="m-0"><i class="fas fa-paw mr-2"></i> Especies</h2>
            <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus mr-1"></i> Nueva Especie
            </button>
        </div>

        <div class="types-body">
            @if(session('ok'))
                <div class="alert alert-success mb-3">{{ session('ok') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-types mb-0">
                    <thead>
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th class="text-center">Estado</th>
                            <th class="text-right" style="width: 160px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i)
                            <tr>
                                <td class="align-middle">{{ $i->id }}</td>
                                <td class="align-middle font-weight-bold">{{ $i->nombre }}</td>
                                <td class="align-middle text-muted">{{ $i->descripcion }}</td>
                                <td class="align-middle text-center">
                                    @if($i->trashed())
                                        <span class="badge-soft" style="background:#e2e3e5; color:#383d41;">⚪ Archivado</span>
                                    @else
                                        <span class="badge-soft" style="background:#d4edda; color:#155724;">🟢 Activo</span>
                                    @endif
                                </td>
                                <td class="text-right align-middle">
                                    <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="editModal({{ $i->id }}, '{{ addslashes($i->nombre) }}', '{{ addslashes($i->descripcion) }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.tipo_animals.destroy', $i->id) }}" method="post" class="d-inline" onsubmit="return confirm('¿Cambiar estado de esta especie?');">
                                        @csrf @method('DELETE')
                                        @if($i->trashed())
                                            <button class="btn btn-success btn-sm rounded-pill px-3" title="Restaurar"><i class="fas fa-undo"></i></button>
                                        @else
                                            <button class="btn btn-secondary btn-sm rounded-pill px-3" title="Archivar (Ocultar)"><i class="fas fa-archive"></i></button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No hay especies registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $items->links() }}</div>
        </div>
    </div>
</div>

<!-- Modal CREAR -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.tipo_animals.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Crear Nueva Especie</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre de la Especie *</label>
                    <input type="text" name="nombre" class="form-control" required placeholder="Ej. Bovino">
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal EDITAR -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editForm" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Editar Especie</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editModal(id, nombre, descripcion) {
        document.getElementById('editForm').action = '/admin/tipo_animals/' + id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_descripcion').value = descripcion;
        $('#editModal').modal('show');
    }
</script>
@endsection