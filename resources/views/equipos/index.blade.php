<x-app-layout>
    <x-slot name="header">Inventario</x-slot>

    <x-ui.page-header title="Inventario de equipos" subtitle="Control de bienes informáticos del soporte técnico.">
        <a href="{{ route('equipos.index') }}" class="btn btn-outline btn-sm">Activos</a>
        <a href="{{ route('equipos.index', ['eliminados' => 1]) }}" class="btn btn-outline btn-sm">Dados de baja</a>
        <a href="{{ route('equipos.create') }}" class="btn btn-primary">
            <x-ui.icon name="plus" class="h-4 w-4" />
            Nuevo equipo
        </a>
    </x-ui.page-header>

    @if (session('success'))
        <x-ui.alert type="success" class="mt-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert type="danger" class="mt-4">{{ session('error') }}</x-ui.alert>
    @endif

    <form method="GET" action="{{ route('equipos.index') }}" class="filter-bar mt-6">
        <input type="text" name="q" value="{{ $filtros['q'] ?? '' }}"
               placeholder="Buscar por código, serie, marca o modelo" class="field">
        <select name="categoria" class="field-select">
            <option value="">Todas las categorías</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(($filtros['categoria'] ?? null) == $categoria->id)>{{ ucfirst($categoria->name) }}</option>
            @endforeach
        </select>
        <select name="estado" class="field-select">
            <option value="">Todos los estados</option>
            <option value="disponible" @selected(($filtros['estado'] ?? null) == 'disponible')>Disponible</option>
            <option value="asignado" @selected(($filtros['estado'] ?? null) == 'asignado')>Asignado</option>
            <option value="mantenimiento" @selected(($filtros['estado'] ?? null) == 'mantenimiento')>En mantenimiento</option>
        </select>
        <div class="flex items-center gap-2">
            <button class="btn btn-primary flex-1">
                <x-ui.icon name="search" class="h-4 w-4" />
                Filtrar
            </button>
            <a href="{{ route('equipos.index') }}" class="btn btn-ghost">Limpiar</a>
        </div>
    </form>

    <div class="table-wrap mt-6">
        <table class="table-ui">
            <thead>
            <tr>
                <th>Código patrimonial</th>
                <th>Categoría</th>
                <th>Marca / Modelo</th>
                <th>Serie</th>
                <th>Especificaciones</th>
                <th>Estado</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($equipos as $equipo)
                <tr>
                    <td class="font-mono text-xs text-slate-600">{{ $equipo->patrimonial_code }}</td>
                    <td>{{ ucfirst($equipo->category->name ?? '—') }}</td>
                    <td>{{ $equipo->brand }} / {{ $equipo->model }}</td>
                    <td class="text-slate-600">{{ $equipo->serial_number ?? '—' }}</td>
                    <td class="text-xs text-slate-600">
                        @if ($equipo->cpu) CPU: {{ $equipo->cpu }}<br>
                        @endif
                        @if ($equipo->ram_gb) RAM: {{ $equipo->ram_gb }} GB<br>
                        @endif
                        @if ($equipo->storage_gb) Almacenamiento: {{ $equipo->storage_gb }} GB {{ strtoupper($equipo->storage_type) }}
                        @endif
                    </td>
                    <td>
                        <x-ui.badge :type="$equipo->status === 'disponible' ? 'success' : ($equipo->status === 'asignado' ? 'info' : 'neutral')"
                                    :label="$equipo->status === 'disponible' ? 'Disponible' : ($equipo->status === 'asignado' ? 'Asignado' : 'Mantenimiento')" />
                    </td>
                    <td class="text-right">
                        @if (request()->boolean('eliminados'))
                            <form method="POST" action="{{ route('equipos.restore', $equipo->id) }}">
                                @csrf
                                @method('PATCH')
                                <button class="link-action">Restaurar</button>
                            </form>
                        @else
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('equipos.edit', $equipo) }}" class="link-action">Editar</a>
                                <form method="POST" action="{{ route('equipos.estado', $equipo) }}" class="flex items-center gap-1.5">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="h-8 rounded-lg border-slate-300 py-0 text-xs">
                                        <option value="disponible">Disponible</option>
                                        <option value="mantenimiento">Mantenimiento</option>
                                        <option value="asignado">Asignado</option>
                                    </select>
                                    <button class="link-action">Cambiar</button>
                                </form>
                                @if ($equipo->status !== 'asignado')
                                    <form method="POST" action="{{ route('equipos.destroy', $equipo) }}" onsubmit="return confirm('¿Dar de baja este equipo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="link-danger">Baja</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">No hay equipos registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $equipos->links() }}
    </div>
</x-app-layout>
