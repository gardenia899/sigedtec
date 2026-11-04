<x-app-layout>
    <x-slot name="header">Reportes</x-slot>

    <x-ui.page-header title="Historial de asignaciones" subtitle="Equipos entregados por solicitud, con su responsable.">
        <a href="{{ route('reportes.asignaciones') }}" class="btn btn-outline">Limpiar filtros</a>
    </x-ui.page-header>

    <form method="GET" action="{{ route('reportes.asignaciones') }}" class="filter-bar mt-6 md:grid-cols-4">
        <select name="oficina" class="field-select">
            <option value="">Todas las oficinas</option>
            @foreach ($oficinas as $oficina)
                <option value="{{ $oficina->id }}" @selected(($filtros['oficina'] ?? null) == $oficina->id)>{{ $oficina->name }}</option>
            @endforeach
        </select>
        <input type="date" name="desde" value="{{ $filtros['desde'] ?? '' }}" class="field" aria-label="Desde">
        <input type="date" name="hasta" value="{{ $filtros['hasta'] ?? '' }}" class="field" aria-label="Hasta">
        <button class="btn btn-primary">
            <x-ui.icon name="search" class="h-4 w-4" />
            Filtrar
        </button>
    </form>

    <div class="table-wrap mt-6">
        <table class="table-ui">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Solicitud</th>
                <th>Oficina</th>
                <th>Solicitante</th>
                <th>Categoría</th>
                <th>Equipo</th>
                <th>Asignado por</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($items as $item)
                <tr>
                    <td class="text-slate-600">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td class="font-mono text-xs text-slate-500">#{{ str_pad($item->request_id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->request->office->name }}</td>
                    <td>{{ $item->request->user->name }}</td>
                    <td>{{ ucfirst($item->request->category->name) }}</td>
                    <td class="text-slate-600">{{ $item->equipment->patrimonial_code }} · {{ $item->equipment->brand }} {{ $item->equipment->model }}</td>
                    <td class="text-slate-600">{{ $item->assignedBy->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">No hay asignaciones para los filtros seleccionados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
</x-app-layout>
