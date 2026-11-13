<x-app-layout>
    <x-slot name="header">Reportes</x-slot>

    <x-ui.page-header title="Solicitudes por estado" subtitle="Listado de todas las solicitudes registradas en el sistema.">
        <a href="{{ route('reportes.solicitudes') }}" class="btn btn-outline">Limpiar filtros</a>
        <a href="{{ route('reportes.solicitudes.pdf', $filtros) }}" class="btn btn-primary">
            <x-ui.icon name="file-text" class="h-4 w-4" /> Exportar PDF
        </a>
    </x-ui.page-header>

    <form method="GET" action="{{ route('reportes.solicitudes') }}" class="filter-bar mt-6 md:grid-cols-3">
        <select name="estado" class="field-select">
            <option value="">Todos los estados</option>
            <option value="pendiente" @selected(($filtros['estado'] ?? null) == 'pendiente')>Pendiente</option>
            <option value="aprobada" @selected(($filtros['estado'] ?? null) == 'aprobada')>Aprobada</option>
            <option value="rechazada" @selected(($filtros['estado'] ?? null) == 'rechazada')>Rechazada</option>
            <option value="cancelada" @selected(($filtros['estado'] ?? null) == 'cancelada')>Cancelada</option>
        </select>
        <select name="oficina" class="field-select">
            <option value="">Todas las oficinas</option>
            @foreach ($oficinas as $oficina)
                <option value="{{ $oficina->id }}" @selected(($filtros['oficina'] ?? null) == $oficina->id)>{{ $oficina->name }}</option>
            @endforeach
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
                <th>#</th>
                <th>Oficina</th>
                <th>Solicitante</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($solicitudes as $solicitud)
                <tr>
                    <td class="font-mono text-xs text-slate-500">#{{ str_pad($solicitud->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $solicitud->office->name }}</td>
                    <td>{{ $solicitud->user->name }}</td>
                    <td>{{ ucfirst($solicitud->category->name) }}</td>
                    <td>{{ $solicitud->quantity }}</td>
                    <td class="text-slate-600">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <x-ui.badge :type="$solicitud->status === 'pendiente' ? 'warning' : ($solicitud->status === 'aprobada' ? 'success' : ($solicitud->status === 'rechazada' ? 'danger' : 'neutral'))"
                                    :label="ucfirst($solicitud->status)" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">No hay solicitudes para los filtros seleccionados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $solicitudes->links() }}</div>
</x-app-layout>
