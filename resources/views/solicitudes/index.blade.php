<x-app-layout>
    <x-slot name="header">Mis solicitudes</x-slot>

    <x-ui.page-header title="Solicitudes de equipos" subtitle="Consulta el estado de las solicitudes que has enviado.">
        <a href="{{ route('solicitudes.create') }}" class="btn btn-primary">
            <x-ui.icon name="plus" class="h-4 w-4" />
            Nueva solicitud
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
                <th>#</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($solicitudes as $solicitud)
                <tr>
                    <td class="font-mono text-xs text-slate-500">#{{ str_pad($solicitud->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ ucfirst($solicitud->category->name ?? '—') }}</td>
                    <td>{{ $solicitud->quantity }}</td>
                    <td class="text-slate-600">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <x-ui.badge :type="$solicitud->status === 'pendiente' ? 'warning' : ($solicitud->status === 'aprobada' ? 'success' : ($solicitud->status === 'rechazada' ? 'danger' : 'neutral'))"
                                    :label="ucfirst($solicitud->status)" />
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('solicitudes.show', $solicitud) }}" class="link-action">Ver</a>
                            @if ($solicitud->status === 'pendiente')
                                <form method="POST" action="{{ route('solicitudes.cancelar', $solicitud) }}" onsubmit="return confirm('¿Cancelar esta solicitud?');">
                                    @csrf
                                    <button class="link-danger">Cancelar</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-state">No tienes solicitudes registradas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $solicitudes->links() }}
    </div>
</x-app-layout>
