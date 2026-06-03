<div class="mb-4">
    <div class="btn-group btn-group-toggle flex-wrap" role="group" aria-label="Tabs gestor ganado">
        <a href="{{ route('admin.tipo_animals.index') }}"
            class="btn btn-sm mr-2 mb-2 {{ request()->routeIs('admin.tipo_animals.*') ? 'active bg-success' : 'text-secondary' }} font-weight-bold"
            style="border-radius: 8px;">
            Especies
        </a>
        <a href="{{ route('admin.razas.index') }}"
            class="btn btn-sm mr-2 mb-2 {{ request()->routeIs('admin.razas.*') ? 'active bg-success' : 'text-secondary' }} font-weight-bold"
            style="border-radius: 8px;">
            Razas
        </a>
        <a href="{{ Route::has('admin.propositos.index') ? route('admin.propositos.index') : url('admin/propositos') }}"
            class="btn btn-sm mr-2 mb-2 {{ request()->routeIs('admin.propositos.*') ? 'active bg-success' : 'text-secondary' }} font-weight-bold"
            style="border-radius: 8px;">
            Propósitos
        </a>
        <a href="{{ route('admin.tipo-pesos.index') }}"
            class="btn btn-sm mr-2 mb-2 {{ request()->routeIs('admin.tipo-pesos.*') ? 'active bg-success' : 'text-secondary' }} font-weight-bold"
            style="border-radius: 8px;">
            Pesaje
        </a>
        <a href="{{ route('admin.unidades_organicos.index') }}"
            class="btn btn-sm mr-2 mb-2 {{ request()->routeIs('admin.unidades_organicos.*') ? 'active bg-success' : 'text-secondary' }} font-weight-bold"
            style="border-radius: 8px;">
            Unidades
        </a>
    </div>
</div>
