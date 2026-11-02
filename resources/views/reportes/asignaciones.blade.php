<x-app-layout>
    <x-slot name="header">Reportes</x-slot>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-800">Historial de asignaciones</h2>
        <p class="text-sm text-slate-500">Equipos asignados por solicitud.</p>
    </div>

    <form method="GET" action="{{ route('reportes.asignaciones') }}" class="mb-6 grid gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-4">
        <select name="oficina" class="rounded-md border-slate-300 text-sm">
            <option value="">Todas las oficinas</option>
            @foreach ($oficinas as $oficina)
                <option value="{{ $oficina->id }}" @selected(($filtros['oficina'] ?? null) == $oficina->id)>{{ $oficina->name }}</option>
            @endforeach
        </select>
        <input type="date" name="desde" value="{{ $filtros['desde'] ?? '' }}" class="rounded-md border-slate-300 text-sm">
        <input type="date" name="hasta" value="{{ $filtros['hasta'] ?? '' }}" class="rounded-md border-slate-300 text-sm">
        <div class="flex items-center gap-2">
            <button class="rounded-md bg-slate-800 px-3 py-2 text-sm text-white">Filtrar</button>
            <a href="{{ route('reportes.asignaciones') }}" class="text-sm text-slate-600">Limpiar</a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
            <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-2.5">Fecha</th>
                <th class="px-4 py-2.5">Solicitud</th>
                <th class="px-4 py-2.5">Oficina</th>
                <th class="px-4 py-2.5">Solicitante</th>
                <th class="px-4 py-2.5">Categoría</th>
                <th class="px-4 py-2.5">Equipo</th>
                <th class="px-4 py-2.5">Asignado por</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($items as $item)
                <tr>
                    <td class="px-4 py-3 text-slate-600">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-slate-600">#{{ $item->request_id }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $item->request->office->name }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $item->request->user->name }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ ucfirst($item->request->category->name) }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $item->equipment->patrimonial_code }} - {{ $item->equipment->brand }} {{ $item->equipment->model }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $item->assignedBy->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">No hay asignaciones.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
</x-app-layout>
