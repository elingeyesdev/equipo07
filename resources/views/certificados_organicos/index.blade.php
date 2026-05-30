@extends('layouts.adminlte')

@section('title', 'Certificados Organicos')
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

    <div class="card card-outline card-danger mb-4">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Certificados obligatorios</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($obligatorios as $certificado)
                            <tr>
                                <td><strong>{{ $certificado->nombre }}</strong></td>
                                <td>{{ $certificado->descripcion }}</td>
                                <td><span class="badge badge-danger">Bloqueado</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No hay certificados obligatorios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-outline card-success">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h3 class="card-title font-weight-bold">Certificados opcionales</h3>
            </div>
            <button type="button" class="btn btn-success mt-3 mt-md-0" onclick="abrirModalCrear()">
                <i class="fas fa-plus mr-2"></i> Nuevo certificado
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Visible</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td><strong>{{ $item->nombre }}</strong></td>
                                <td>{{ Str::limit($item->descripcion, 90, '...') }}</td>
                                <td>
                                    <span class="badge badge-{{ $item->activo ? 'success' : 'secondary' }}">
                                        {{ $item->activo ? 'Activo' : 'Oculto' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-sm btn-outline-primary mr-1"
                                        onclick='abrirModalEditar(@json($item->id), @json($item->nombre), @json($item->descripcion), @json($item->activo))'>
                                        <i class="fas fa-pen mr-1"></i> Editar
                                    </button>
                                    <form action="{{ route('admin.certificados_organicos.destroy', $item) }}" method="POST" class="d-inline delete-form" data-message="Â¿EstÃ¡ seguro de eliminar {{ $item->nombre }}?">
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
                                <td colspan="4" class="text-center text-muted py-5">No hay certificados opcionales registrados aun.</td>
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
                    <h5 class="modal-title" id="modalTitle">Agregar certificado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formularioGestor" method="POST">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputNombre">Nombre del certificado *</label>
                            <input type="text" class="form-control" id="inputNombre" name="nombre" required maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="inputDescripcion">Descripcion breve *</label>
                            <textarea class="form-control" id="inputDescripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="custom-control custom-switch" id="grupoActivo">
                            <input type="checkbox" class="custom-control-input" id="inputActivo" name="activo" value="1" checked>
                            <label class="custom-control-label" for="inputActivo">Visible al registrar organicos</label>
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
    const urlStore = "{{ route('admin.certificados_organicos.store') }}";
    const urlUpdateBase = "{{ url('admin/certificados_organicos') }}";

    function abrirModalCrear() {
        $('#modalTitle').text('Agregar certificado');
        $('#formularioGestor').attr('action', urlStore);
        $('#formMethod').val('POST');
        $('#inputNombre').val('');
        $('#inputDescripcion').val('');
        $('#inputActivo').prop('checked', true);
        $('#grupoActivo').hide();
        $('#modalGestor').modal('show');
    }

    function abrirModalEditar(id, nombre, descripcion, activo) {
        $('#modalTitle').text('Editar certificado');
        $('#formularioGestor').attr('action', urlUpdateBase + '/' + id);
        $('#formMethod').val('PUT');
        $('#inputNombre').val(nombre);
        $('#inputDescripcion').val(descripcion || '');
        $('#inputActivo').prop('checked', Boolean(activo));
        $('#grupoActivo').show();
        $('#modalGestor').modal('show');
    }
</script>
@endsection
