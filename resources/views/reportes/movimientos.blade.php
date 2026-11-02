<x-app-layout>
    <x-slot name="header">Reportes</x-slot>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-800">Movimientos</h2>
        <p class="text-sm text-slate-500">Bitácora de cambios de estado.</p>
    </div>

    <form method="GET" action="{{ route('reportes.movimientos') }}" class="mb-6 grid gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-3">
        <input type="date" name="desde" value="{{ $filtros['desde'] ?? '' }}" class="rounded-md border-slate-300 text-sm">
        <input type="date" name="hasta" value="{{ $filtros['hasta'] ?? '' }}" class="rounded-md border-slate-300 text-sm">
        <div class="flex items-center gap-2">
            <button class="rounded-md bg-slate-800 px-3 py-2 text-sm text-white">Filtrar</button>
            <a href="{{ route('reportes.movimientos') }}" class="text-sm text-slate-600">Limpiar</a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
            <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-2.5">Fecha</th>
                <th class="px-4 py-2.5">Equipo</th>
                <th class="px-4 py-2.5">De</th>
                <th class="px-4 py-2.5">A</th>
                <th class="px-4 py-2.5">Motivo</th>
                <th class="px-4 py-2.5">Usuario</th>
                <th class="px-4 py-2.5">Nota</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($movimientos as $mov)
                <tr>
                    <td class="px-4 py-3 text-slate-600">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $mov->equipment->patrimonial_code ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $mov->from_status ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $mov->to_status }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $mov->reason }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $mov->user->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $mov->notes ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">No hay movimientos.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $movimientos->links() }}</div>
</x-app-layout>
