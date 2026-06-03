@extends('layouts.adminlte')

@section('title', 'Unidades de Medida')
@section('page_title', 'Gestor de Selectores Organicos')

@section('content')
    @include('components.tabs-gestor-organicos')

    @if(session('ok') || session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Listo.</strong> {{ session('ok') ?? session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-outline card-success">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h3 class="card-title font-weight-bold">Unidades de Medida</h3>
            </div>
            <button type="button" class="btn btn-success mt-3 mt-md-0" onclick="abrirModalCrear()">
                <i class="fas fa-plus mr-2"></i> Nuevo Registro
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nombre de la Unidad</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td><strong>{{ $item->nombre }}</strong></td>
                                <td>{{ Str::limit($item->descripcion, 60, '...') }}</td>
                                <td><span class="badge badge-success">Activo</span></td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-sm btn-outline-primary mr-1"
                                        onclick="abrirModalEditar({{ $item->id }}, '{{ addslashes($item->nombre) }}', '{{ addslashes($item->descripcion) }}')">
                                        <i class="fas fa-pen mr-1"></i> Editar
                                    </button>
                                    <form action="{{ route('admin.unidades_organicos.destroy', $item) }}" method="POST" class="d-inline delete-form" data-message="¿Está seguro de eliminar {{ $item->nombre }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">No hay unidades registradas aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer clearfix">
            {{ $items->links() ?? '' }}
        </div>
    </div>

    <div class="modal fade" id="modalGestor" tabindex="-1" role="dialog" aria-labelledby="modalGestorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Agregar Unidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formularioGestor" method="POST">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputNombre">Nombre *</label>
                            <input type="text" class="form-control" id="inputNombre" name="nombre" required maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="inputDescripcion">Descripción</label>
                            <textarea class="form-control" id="inputDescripcion" name="descripcion" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    const urlStore = "{{ route('admin.unidades_organicos.store') }}";
    const urlUpdateBase = "{{ url('admin/unidades_organicos') }}";

    function abrirModalCrear() {
        $('#modalTitle').text('Agregar Unidad');
        $('#formularioGestor').attr('action', urlStore);
        $('#formMethod').val('POST');
        $('#inputNombre').val('');
        $('#inputDescripcion').val('');
        $('#modalGestor').modal('show');
    }

    function abrirModalEditar(id, nombre, descripcion) {
        $('#modalTitle').text('Editar Unidad');
        $('#formularioGestor').attr('action', urlUpdateBase + '/' + id);
        $('#formMethod').val('PUT');
        $('#inputNombre').val(nombre);
        $('#inputDescripcion').val(descripcion);
        $('#modalGestor').modal('show');
    }
</script>
@endsection
