<div class="mb-4">
    <div class="btn-group btn-group-toggle flex-wrap" role="group" aria-label="Tabs gestor organicos">
        <a href="{{ route('admin.tipo_cultivos.index') }}"
            class="btn btn-sm mr-2 mb-2 {{ request()->routeIs('admin.tipo_cultivos.*') ? 'active bg-success' : 'text-secondary' }} font-weight-bold"
            style="border-radius: 8px;">
            Tipos de cultivo
        </a>
        <a href="{{ route('admin.unidades_organicos.index') }}"
            class="btn btn-sm mr-2 mb-2 {{ request()->routeIs('admin.unidades_organicos.*') ? 'active bg-success' : 'text-secondary' }} font-weight-bold"
            style="border-radius: 8px;">
            Unidades de medida
        </a>
        <a href="{{ route('admin.certificados_organicos.index') }}"
            class="btn btn-sm mr-2 mb-2 {{ request()->routeIs('admin.certificados_organicos.*') ? 'active bg-success' : 'text-secondary' }} font-weight-bold"
            style="border-radius: 8px;">
            Certificados
        </a>
    </div>
</div>
