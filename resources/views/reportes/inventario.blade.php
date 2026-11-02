<x-app-layout>
    <x-slot name="header">Reportes</x-slot>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-800">Inventario</h2>
        <p class="text-sm text-slate-500">Listado general de equipos.</p>
    </div>

    <form method="GET" action="{{ route('reportes.inventario') }}" class="mb-6 grid gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-4">
        <input type="text" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Buscar código, marca o modelo" class="rounded-md border-slate-300 text-sm">
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
            <option value="mantenimiento" @selected(($filtros['estado'] ?? null) == 'mantenimiento')>Mantenimiento</option>
        </select>
        <div class="flex items-center gap-2">
            <button class="rounded-md bg-slate-800 px-3 py-2 text-sm text-white">Filtrar</button>
            <a href="{{ route('reportes.inventario') }}" class="text-sm text-slate-600">Limpiar</a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
            <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-2.5">Código</th>
                <th class="px-4 py-2.5">Categoría</th>
                <th class="px-4 py-2.5">Marca / Modelo</th>
                <th class="px-4 py-2.5">Serie</th>
                <th class="px-4 py-2.5">Estado</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($equipos as $equipo)
                <tr>
                    <td class="px-4 py-3 font-mono text-xs text-slate-700">{{ $equipo->patrimonial_code }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ ucfirst($equipo->category->name ?? '—') }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $equipo->brand }} / {{ $equipo->model }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $equipo->serial_number ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ ucfirst($equipo->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">No hay equipos.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $equipos->links() }}</div>
</x-app-layout>
