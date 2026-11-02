<x-app-layout>
    <x-slot name="header">Reportes</x-slot>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-800">Solicitudes por estado</h2>
        <p class="text-sm text-slate-500">Listado de todas las solicitudes.</p>
    </div>

    <form method="GET" action="{{ route('reportes.solicitudes') }}" class="mb-6 grid gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-3">
        <select name="estado" class="rounded-md border-slate-300 text-sm">
            <option value="">Todos los estados</option>
            <option value="pendiente" @selected(($filtros['estado'] ?? null) == 'pendiente')>Pendiente</option>
            <option value="aprobada" @selected(($filtros['estado'] ?? null) == 'aprobada')>Aprobada</option>
            <option value="rechazada" @selected(($filtros['estado'] ?? null) == 'rechazada')>Rechazada</option>
            <option value="cancelada" @selected(($filtros['estado'] ?? null) == 'cancelada')>Cancelada</option>
        </select>
        <select name="oficina" class="rounded-md border-slate-300 text-sm">
            <option value="">Todas las oficinas</option>
            @foreach ($oficinas as $oficina)
                <option value="{{ $oficina->id }}" @selected(($filtros['oficina'] ?? null) == $oficina->id)>{{ $oficina->name }}</option>
            @endforeach
        </select>
        <div class="flex items-center gap-2">
            <button class="rounded-md bg-slate-800 px-3 py-2 text-sm text-white">Filtrar</button>
            <a href="{{ route('reportes.solicitudes') }}" class="text-sm text-slate-600">Limpiar</a>
        </div>
    </form>

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
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($solicitudes as $solicitud)
                <tr>
                    <td class="px-4 py-3 text-slate-600">{{ $solicitud->id }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $solicitud->office->name }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $solicitud->user->name }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ ucfirst($solicitud->category->name) }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $solicitud->quantity }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ ucfirst($solicitud->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">No hay solicitudes.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $solicitudes->links() }}</div>
</x-app-layout>
