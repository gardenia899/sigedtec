<x-app-layout>
    <x-slot name="header">Categorías</x-slot>

    <x-ui.page-header title="Categorías de equipos" subtitle="Clasificación utilizada por el inventario.">
        <a href="{{ route('categorias.create') }}" class="btn btn-primary">
            <x-ui.icon name="plus" class="h-4 w-4" />
            Nueva categoría
        </a>
    </x-ui.page-header>

    @if (session('success'))
        <x-ui.alert type="success" class="mt-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert type="danger" class="mt-4">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="table-wrap mt-6">
        <table class="table-ui">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th class="text-center">Equipos</th>
                <th>Estado</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($categorias as $categoria)
                <tr>
                    <td class="font-medium capitalize text-slate-800">{{ $categoria->name }}</td>
                    <td class="text-slate-600">{{ $categoria->description ?? '—' }}</td>
                    <td class="text-center text-slate-600">{{ $categoria->equipments_count }}</td>
                    <td>
                        <x-ui.badge :type="$categoria->is_active ? 'success' : 'neutral'"
                                    :label="$categoria->is_active ? 'Activa' : 'Inactiva'" />
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('categorias.edit', $categoria) }}" class="link-action">Editar</a>
                            <form method="POST" action="{{ route('categorias.toggle', $categoria) }}">
                                @csrf
                                @method('PATCH')
                                <button class="{{ $categoria->is_active ? 'link-danger' : 'link-action' }}" type="submit">
                                    {{ $categoria->is_active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">No hay categorías registradas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
