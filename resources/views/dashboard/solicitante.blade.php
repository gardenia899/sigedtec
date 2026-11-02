<x-app-layout>
    <x-slot name="header">Panel de mi oficina</x-slot>

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Mi oficina</h2>
            <p class="text-sm text-slate-500">{{ auth()->user()->office->name ?? '' }}</p>
        </div>
        <a href="{{ route('solicitudes.create') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Nueva solicitud</a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        @foreach ([
            ['titulo' => 'Mis solicitudes pendientes', 'valor' => $pendientes, 'color' => 'text-amber-600'],
            ['titulo' => 'Mis solicitudes registradas', 'valor' => $solicitudes->count(), 'color' => 'text-slate-700'],
            ['titulo' => 'Equipos asignados a mi oficina', 'valor' => $equiposOficina, 'color' => 'text-sky-600'],
        ] as $tarjeta)
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $tarjeta['titulo'] }}</p>
                <p class="mt-2 text-2xl font-semibold {{ $tarjeta['color'] }}">{{ $tarjeta['valor'] }}</p>
            </div>
        @endforeach
    </div>

    <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-4 py-3 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-700">Mis solicitudes recientes</h2>
        </div>

        @if ($solicitudes->isEmpty())
            <p class="p-4 text-sm text-slate-500">Todavía no ha registrado solicitudes.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-2.5">Categoría</th>
                        <th class="px-4 py-2.5">Cantidad</th>
                        <th class="px-4 py-2.5">Fecha</th>
                        <th class="px-4 py-2.5">Estado</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @foreach ($solicitudes as $solicitud)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $solicitud->category->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $solicitud->quantity }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                @php $mapa = ['pendiente' => 'warning', 'aprobada' => 'success', 'rechazada' => 'danger', 'cancelada' => 'neutral']; @endphp
                                <x-ui.badge :type="$mapa[$solicitud->status]" :label="ucfirst($solicitud->status)" />
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-app-layout>
