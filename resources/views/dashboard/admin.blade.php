<x-app-layout>
    <x-slot name="header">Panel de Soporte Técnico</x-slot>

    {{-- Indicadores reales de PostgreSQL (SDD §14-M8, RN-22) --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['titulo' => 'Equipos disponibles', 'valor' => $disponibles, 'color' => 'text-green-600', 'pista' => 'Listos para despacho'],
            ['titulo' => 'Equipos asignados', 'valor' => $asignados, 'color' => 'text-sky-600', 'pista' => 'En oficinas'],
            ['titulo' => 'En mantenimiento', 'valor' => $mantenimiento, 'color' => 'text-amber-600', 'pista' => 'Fuera de servicio'],
            ['titulo' => 'Solicitudes pendientes', 'valor' => $pendientes, 'color' => 'text-slate-700', 'pista' => 'Requieren decisión'],
        ] as $tarjeta)
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $tarjeta['titulo'] }}</p>
                <p class="mt-2 text-2xl font-semibold {{ $tarjeta['color'] }}">{{ $tarjeta['valor'] }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $tarjeta['pista'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        {{-- Solicitudes recientes --}}
        <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3">
                <h2 class="text-sm font-semibold text-slate-700">Solicitudes recientes</h2>
            </div>
            @if ($solicitudesRecientes->isEmpty())
                <p class="p-4 text-sm text-slate-500">Aún no hay solicitudes registradas.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($solicitudesRecientes as $solicitud)
                        <li class="flex items-center gap-3 px-4 py-3 text-sm">
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium text-slate-800">
                                    {{ $solicitud->quantity }} × {{ $solicitud->category->name }}
                                </p>
                                <p class="truncate text-xs text-slate-500">
                                    {{ $solicitud->office->name }} · {{ $solicitud->user->name }}
                                </p>
                            </div>
                            @php $mapa = ['pendiente' => 'warning', 'aprobada' => 'success', 'rechazada' => 'danger', 'cancelada' => 'info']; @endphp
                            <x-ui.badge :type="$mapa[$solicitud->status]" :label="ucfirst($solicitud->status)" />
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- Movimientos recientes --}}
        <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3">
                <h2 class="text-sm font-semibold text-slate-700">Movimientos recientes de inventario</h2>
            </div>
            @if ($movimientosRecientes->isEmpty())
                <p class="p-4 text-sm text-slate-500">Sin movimientos registrados.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($movimientosRecientes as $movimiento)
                        <li class="flex items-center gap-3 px-4 py-3 text-sm">
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium text-slate-800">
                                    {{ $movimiento->equipment->patrimonial_code ?? '—' }}
                                    <span class="font-normal text-slate-500">→ {{ $movimiento->to_status }}</span>
                                </p>
                                <p class="truncate text-xs text-slate-500">
                                    {{ $movimiento->reason }} · {{ $movimiento->user->name ?? 'Sistema' }}
                                </p>
                            </div>
                            <time class="shrink-0 text-xs text-slate-400" datetime="{{ $movimiento->created_at->toIso8601String() }}">
                                {{ $movimiento->created_at->format('d/m/Y H:i') }}
                            </time>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>

    {{-- Distribución por categoría --}}
    <section class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="text-sm font-semibold text-slate-700">Equipos por categoría</h2>
        <div class="mt-4 space-y-3">
            @php $maximo = max(1, $porCategoria->max('equipments_count')); @endphp
            @foreach ($porCategoria as $categoria)
                <div class="flex items-center gap-3 text-sm">
                    <span class="w-48 shrink-0 truncate text-slate-600">{{ $categoria->name }}</span>
                    <div class="h-3 flex-1 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-blue-600" style="width: {{ round($categoria->equipments_count * 100 / $maximo) }}%"></div>
                    </div>
                    <span class="w-8 text-right font-medium text-slate-700">{{ $categoria->equipments_count }}</span>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>