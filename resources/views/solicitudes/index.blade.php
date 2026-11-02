<x-app-layout>
    <x-slot name="header">Mis solicitudes</x-slot>

    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Solicitudes de equipos</h2>
            <p class="text-sm text-slate-500">Consulta el estado de tus solicitudes.</p>
        </div>
        <a href="{{ route('solicitudes.create') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Nueva solicitud</a>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 rounded-md bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="mt-6 overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
            <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-2.5">#</th>
                <th class="px-4 py-2.5">Categoría</th>
                <th class="px-4 py-2.5">Cantidad</th>
                <th class="px-4 py-2.5">Fecha</th>
                <th class="px-4 py-2.5">Estado</th>
                <th class="px-4 py-2.5 text-right">Acciones</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($solicitudes as $solicitud)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-slate-600">{{ $solicitud->id }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ ucfirst($solicitud->category->name ?? '—') }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $solicitud->quantity }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <x-ui.badge :type="$solicitud->status === 'pendiente' ? 'warning' : ($solicitud->status === 'aprobada' ? 'success' : ($solicitud->status === 'rechazada' ? 'danger' : 'neutral'))"
                                    :label="ucfirst($solicitud->status)" />
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('solicitudes.show', $solicitud) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Ver</a>
                            @if ($solicitud->status === 'pendiente')
                                <form method="POST" action="{{ route('solicitudes.cancelar', $solicitud) }}" onsubmit="return confirm('¿Cancelar esta solicitud?');">
                                    @csrf
                                    <button class="text-sm text-red-600 hover:text-red-800">Cancelar</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">
                        No tienes solicitudes registradas.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $solicitudes->links() }}
    </div>
</x-app-layout>
