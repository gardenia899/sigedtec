<x-app-layout>
    <x-slot name="header">Inventario</x-slot>

    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Inventario de equipos</h2>
            <p class="text-sm text-slate-500">Control de bienes informáticos.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('equipos.index') }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Activos</a>
            <a href="{{ route('equipos.index', ['eliminados' => 1]) }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Dados de baja</a>
            <a href="{{ route('equipos.create') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Nuevo equipo</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 rounded-md bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('equipos.index') }}" class="mt-6 grid gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-4">
        <input type="text" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Buscar por código, serie, marca o modelo" class="rounded-md border-slate-300 text-sm">
        <select name="categoria" class="rounded-md border-slate-300 text-sm">
            <option value="">Todas las categorías</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(($filtros['categoria'] ?? null) == $categoria->id)>{{ ucfirst($categoria->name) }}</option>
            @endforeach
        </select>
        <select name="estado" class="rounded-md border-slate-300 text-sm">
            <option value="">Todos los estados</option>
            <option value="disponible" @selected(($filtros['estado'] ?? null) == 'disponible')>Disponible</option>
            <option value="asignado" @selected(($filtros['estado'] ?? null) == 'asignado')>Asignado</option>
            <option value="mantenimiento" @selected(($filtros['estado'] ?? null) == 'mantenimiento')>En mantenimiento</option>
        </select>
        <div class="flex items-center gap-2">
            <button class="rounded-md bg-slate-800 px-3 py-2 text-sm text-white">Filtrar</button>
            <a href="{{ route('equipos.index') }}" class="text-sm text-slate-600">Limpiar</a>
        </div>
    </form>

    <div class="mt-6 overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
            <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-2.5">Código patrimonial</th>
                <th class="px-4 py-2.5">Categoría</th>
                <th class="px-4 py-2.5">Marca / Modelo</th>
                <th class="px-4 py-2.5">Serie</th>
                <th class="px-4 py-2.5">Especificaciones</th>
                <th class="px-4 py-2.5">Estado</th>
                <th class="px-4 py-2.5 text-right">Acciones</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($equipos as $equipo)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs text-slate-700">{{ $equipo->patrimonial_code }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ ucfirst($equipo->category->name ?? '—') }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $equipo->brand }} / {{ $equipo->model }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $equipo->serial_number ?? '—' }}</td>
                    <td class="px-4 py-3 text-xs text-slate-600">
                        @if ($equipo->cpu) CPU: {{ $equipo->cpu }}<br>
                        @endif
                        @if ($equipo->ram_gb) RAM: {{ $equipo->ram_gb }} GB<br>
                        @endif
                        @if ($equipo->storage_gb) Almacenamiento: {{ $equipo->storage_gb }} GB {{ strtoupper($equipo->storage_type) }}
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <x-ui.badge :type="$equipo->status === 'disponible' ? 'success' : ($equipo->status === 'asignado' ? 'warning' : 'neutral')"
                                    :label="$equipo->status === 'disponible' ? 'Disponible' : ($equipo->status === 'asignado' ? 'Asignado' : 'Mantenimiento')" />
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if (request()->boolean('eliminados'))
                            <form method="POST" action="{{ route('equipos.restore', $equipo->id) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-sm text-indigo-600 hover:text-indigo-800">Restaurar</button>
                            </form>
                        @else
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('equipos.edit', $equipo) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Editar</a>
                                <form method="POST" action="{{ route('equipos.estado', $equipo) }}" class="flex items-center gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded-md border-slate-300 text-xs">
                                        <option value="disponible">Disponible</option>
                                        <option value="mantenimiento">Mantenimiento</option>
                                        <option value="asignado">Asignado</option>
                                    </select>
                                    <button class="text-xs text-slate-600">Cambiar</button>
                                </form>
                                @if ($equipo->status !== 'asignado')
                                    <form method="POST" action="{{ route('equipos.destroy', $equipo) }}" onsubmit="return confirm('¿Dar de baja este equipo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-sm text-red-600 hover:text-red-800">Baja</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">
                        No hay equipos registrados.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $equipos->links() }}
    </div>
</x-app-layout>
