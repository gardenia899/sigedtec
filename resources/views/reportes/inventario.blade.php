<x-app-layout>
    <x-slot name="header">Reportes</x-slot>

    <x-ui.page-header title="Reporte de inventario" subtitle="Listado general de equipos registrados en el sistema.">
        <a href="{{ route('reportes.inventario') }}" class="btn btn-outline">Limpiar filtros</a>
        <a href="{{ route('reportes.inventario.pdf', $filtros) }}" class="btn btn-primary">
            <x-ui.icon name="file-text" class="h-4 w-4" /> Exportar PDF
        </a>
    </x-ui.page-header>

    <form method="GET" action="{{ route('reportes.inventario') }}" class="filter-bar mt-6">
        <input type="text" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Buscar código, marca o modelo" class="field">
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
            <option value="mantenimiento" @selected(($filtros['estado'] ?? null) == 'mantenimiento')>Mantenimiento</option>
        </select>
        <button class="btn btn-primary">
            <x-ui.icon name="search" class="h-4 w-4" />
            Filtrar
        </button>
    </form>

    <div class="table-wrap mt-6">
        <table class="table-ui">
            <thead>
            <tr>
                <th>Código</th>
                <th>Categoría</th>
                <th>Marca / Modelo</th>
                <th>Serie</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($equipos as $equipo)
                <tr>
                    <td class="font-mono text-xs text-slate-600">{{ $equipo->patrimonial_code }}</td>
                    <td>{{ ucfirst($equipo->category->name ?? '—') }}</td>
                    <td>{{ $equipo->brand }} / {{ $equipo->model }}</td>
                    <td class="text-slate-600">{{ $equipo->serial_number ?? '—' }}</td>
                    <td>
                        <x-ui.badge :type="$equipo->status === 'disponible' ? 'success' : ($equipo->status === 'asignado' ? 'info' : 'neutral')"
                                    :label="$equipo->status === 'disponible' ? 'Disponible' : ($equipo->status === 'asignado' ? 'Asignado' : 'Mantenimiento')" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">No hay equipos para los filtros seleccionados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $equipos->links() }}</div>
</x-app-layout>
