<x-app-layout>
    <x-slot name="header">Solicitudes</x-slot>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-800">Bandeja de solicitudes</h2>
        <p class="text-sm text-slate-500">Aprobar o rechazar solicitudes de equipos.</p>
    </div>

    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('admin.solicitudes.index', ['estado' => 'pendiente']) }}" class="rounded-md px-3 py-2 text-sm {{ $estado === 'pendiente' ? 'bg-indigo-600 text-white' : 'bg-white text-slate-700 border border-slate-300' }}">Pendientes ({{ $contadores['pendiente'] }})</a>
        <a href="{{ route('admin.solicitudes.index', ['estado' => 'aprobada']) }}" class="rounded-md px-3 py-2 text-sm {{ $estado === 'aprobada' ? 'bg-green-600 text-white' : 'bg-white text-slate-700 border border-slate-300' }}">Aprobadas ({{ $contadores['aprobada'] }})</a>
        <a href="{{ route('admin.solicitudes.index', ['estado' => 'rechazada']) }}" class="rounded-md px-3 py-2 text-sm {{ $estado === 'rechazada' ? 'bg-red-600 text-white' : 'bg-white text-slate-700 border border-slate-300' }}">Rechazadas ({{ $contadores['rechazada'] }})</a>
        <a href="{{ route('admin.solicitudes.index', ['estado' => 'cancelada']) }}" class="rounded-md px-3 py-2 text-sm {{ $estado === 'cancelada' ? 'bg-slate-600 text-white' : 'bg-white text-slate-700 border border-slate-300' }}">Canceladas ({{ $contadores['cancelada'] }})</a>
        <a href="{{ route('admin.solicitudes.index', ['estado' => 'todas']) }}" class="rounded-md px-3 py-2 text-sm {{ $estado === 'todas' ? 'bg-indigo-600 text-white' : 'bg-white text-slate-700 border border-slate-300' }}">Todas</a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
            <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-2.5">#</th>
                <th class="px-4 py-2.5">Oficina</th>
                <th class="px-4 py-2.5">Solicitante</th>
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
                    <td class="px-4 py-3 text-slate-700">{{ $solicitud->office->name }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $solicitud->user->name }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ ucfirst($solicitud->category->name) }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $solicitud->quantity }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <x-ui.badge :type="$solicitud->status === 'pendiente' ? 'warning' : ($solicitud->status === 'aprobada' ? 'success' : ($solicitud->status === 'rechazada' ? 'danger' : 'neutral'))"
                                    :label="ucfirst($solicitud->status)" />
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if ($solicitud->status === 'pendiente')
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.solicitudes.form', $solicitud) }}" class="text-sm text-green-600 hover:text-green-800">Aprobar</a>
                                <a href="{{ route('admin.solicitudes.formRechazar', $solicitud) }}" class="text-sm text-red-600 hover:text-red-800">Rechazar</a>
                            </div>
                        @else
                            <span class="text-sm text-slate-400">Procesada</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">
                        No hay solicitudes en este estado.
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
