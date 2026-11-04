<x-app-layout>
    <x-slot name="header">Oficinas</x-slot>

    <x-ui.page-header title="Catálogo de oficinas" subtitle="Unidades institucionales receptoras de equipos.">
        <a href="{{ route('oficinas.create') }}" class="btn btn-primary">
            <x-ui.icon name="plus" class="h-4 w-4" />
            Nueva oficina
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
                <th>Código</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th class="text-center">Usuarios</th>
                <th>Estado</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($oficinas as $oficina)
                <tr>
                    <td class="font-mono text-xs text-slate-600">{{ $oficina->code }}</td>
                    <td class="font-medium text-slate-800">{{ $oficina->name }}</td>
                    <td class="text-slate-600">{{ $oficina->location ?? '—' }}</td>
                    <td class="text-center text-slate-600">{{ $oficina->users_count }}</td>
                    <td>
                        <x-ui.badge :type="$oficina->is_active ? 'success' : 'neutral'"
                                    :label="$oficina->is_active ? 'Activa' : 'Inactiva'" />
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('oficinas.edit', $oficina) }}" class="link-action">Editar</a>
                            <form method="POST" action="{{ route('oficinas.toggle', $oficina) }}">
                                @csrf
                                @method('PATCH')
                                <button class="{{ $oficina->is_active ? 'link-danger' : 'link-action' }}" type="submit">
                                    {{ $oficina->is_active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-state">No hay oficinas registradas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
