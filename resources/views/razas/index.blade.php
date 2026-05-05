@extends('layouts.adminlte')
@section('title', 'Razas de Ganado')

@section('content')
<style>
    .breeds-card { border-radius: 15px; overflow: hidden; border: 0; box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
    .breeds-header { background: var(--agro); color: #fff; padding: 1.5rem 1.75rem; display: flex; justify-content: space-between; align-items: center; }
    .breeds-body { background: #fff; padding: 1.5rem 1.75rem 1.25rem; }
    .table-breeds thead th { border-top: 0; font-size: .9rem; text-transform: uppercase; color: #6c757d; background-color: #f8f9fa; }
    .badge-soft { padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
</style>

<div class="container-fluid">
    <div class="breeds-card card">
        <div class="breeds-header">
            <h2 class="m-0"><i class="fas fa-dna mr-2"></i> Razas</h2>
            <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus mr-1"></i> Nueva Raza
            </button>
        </div>

        <div class="breeds-body">
            @if(session('success'))
                <div class="alert alert-success mb-3">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-breeds mb-0">
                    <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>Nombre</th>
                            <th>Especie (Paterna)</th>
                            <th class="text-center">Estado</th>
                            <th class="text-right" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($razas as $raza)
                            <tr>
                                <td class="align-middle">{{ $raza->id }}</td>
                                <td class="align-middle font-weight-bold">{{ $raza->nombre }}</td>
                                <td class="align-middle"><span class="badge badge-info px-2 py-1">{{ $raza->tipoAnimal->nombre ?? 'Sin Especie' }}</span></td>
                                <td class="align-middle text-center">
                                    @if($raza->trashed())
                                        <span class="badge-soft" style="background:#e2e3e5; color:#383d41;">⚪ Archivada</span>
                                    @else
                                        <span class="badge-soft" style="background:#d4edda; color:#155724;">🟢 Activa</span>
                                    @endif
                                </td>
                                <td class="text-right align-middle">
                                    <button class="btn btn-warning btn-sm rounded-pill px-3 text-white" onclick="editModal({{ $raza->id }}, '{{ addslashes($raza->nombre) }}', '{{ $raza->tipo_animal_id }}', '{{ addslashes($raza->descripcion) }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.razas.destroy', $raza->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Cambiar estado de esta raza?');">
                                        @csrf @method('DELETE')
                                        @if($raza->trashed())
                                            <button class="btn btn-success btn-sm rounded-pill px-3" title="Restaurar"><i class="fas fa-undo"></i></button>
                                        @else
                                            <button class="btn btn-secondary btn-sm rounded-pill px-3" title="Archivar"><i class="fas fa-archive"></i></button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No hay razas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $razas->links() }}</div>
        </div>
    </div>
</div>

<!-- Modal CREAR -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.razas.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Crear Nueva Raza</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre de la Raza *</label>
                    <input type="text" name="nombre" class="form-control" required placeholder="Ej. Nelore">
                </div>
                <div class="form-group">
                    <label>Pertenece a (Especie) *</label>
                    <select name="tipo_animal_id" class="form-control" required>
                        <option value="">Seleccione una especie...</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2"></textarea>
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
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold">Editar Raza</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Especie *</label>
                    <select name="tipo_animal_id" id="edit_tipo" class="form-control" required>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning font-weight-bold">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editModal(id, nombre, tipoId, descripcion) {
        document.getElementById('editForm').action = '/admin/razas/' + id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_tipo').value = tipoId;
        document.getElementById('edit_descripcion').value = descripcion;
        $('#editModal').modal('show');
    }
</script>
@endsection