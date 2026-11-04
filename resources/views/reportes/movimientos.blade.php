<x-app-layout>
    <x-slot name="header">Reportes</x-slot>

    <x-ui.page-header title="Movimientos de inventario" subtitle="Bitácora de cambios de estado de los equipos.">
        <a href="{{ route('reportes.movimientos') }}" class="btn btn-outline">Limpiar filtros</a>
    </x-ui.page-header>

    <form method="GET" action="{{ route('reportes.movimientos') }}" class="filter-bar mt-6 md:grid-cols-3">
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
                <th>Equipo</th>
                <th>De</th>
                <th>A</th>
                <th>Motivo</th>
                <th>Usuario</th>
                <th>Nota</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($movimientos as $mov)
                <tr>
                    <td class="text-slate-600">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                    <td class="font-mono text-xs text-slate-600">{{ $mov->equipment->patrimonial_code ?? '—' }}</td>
                    <td>
                        @if ($mov->from_status)
                            <x-ui.badge type="neutral" :label="ucfirst($mov->from_status)" />
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td>
                        <x-ui.badge :type="$mov->to_status === 'disponible' ? 'success' : ($mov->to_status === 'asignado' ? 'info' : ($mov->to_status === 'mantenimiento' ? 'warning' : 'neutral'))"
                                    :label="ucfirst($mov->to_status)" />
                    </td>
                    <td class="text-slate-600">{{ $mov->reason }}</td>
                    <td class="text-slate-600">{{ $mov->user->name ?? '—' }}</td>
                    <td class="text-slate-600">{{ $mov->notes ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">No hay movimientos para los filtros seleccionados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $movimientos->links() }}</div>
</x-app-layout>
