<x-app-layout>
    <x-slot name="header">Detalle de solicitud</x-slot>

    <x-ui.page-header :title="'Solicitud #'.str_pad($solicitud->id, 4, '0', STR_PAD_LEFT)"
                      :subtitle="'Registrada el '.$solicitud->created_at->format('d/m/Y H:i')">
        <x-ui.badge :type="$solicitud->status === 'pendiente' ? 'warning' : ($solicitud->status === 'aprobada' ? 'success' : ($solicitud->status === 'rechazada' ? 'danger' : 'neutral'))"
                    :label="ucfirst($solicitud->status)" />
        <a href="{{ route('solicitudes.index') }}" class="btn btn-outline">
            <x-ui.icon name="arrow-right" class="h-4 w-4 rotate-180" />
            Volver
        </a>
    </x-ui.page-header>

    <div class="mt-6 grid gap-5 lg:grid-cols-2">
        <div class="panel">
            <div class="panel-head">
                <h3 class="panel-title">
                    <x-ui.icon name="clipboard" class="h-4 w-4 text-blue-600" />
                    Datos de la solicitud
                </h3>
            </div>
            <dl class="divide-y divide-slate-100 text-[13px]">
                @foreach ([
                    ['Oficina', $solicitud->office->name],
                    ['Categoría', ucfirst($solicitud->category->name)],
                    ['Cantidad', $solicitud->quantity],
                    ['Estado', ucfirst($solicitud->status)],
                    ['Atendida por', $solicitud->procesadaPor?->name],
                    ['Fecha de atención', $solicitud->processed_at?->format('d/m/Y H:i')],
                ] as [$etiqueta, $valor])
                    @if ($valor !== null)
                        <div class="flex items-center justify-between gap-4 px-6 py-3">
                            <dt class="text-slate-500">{{ $etiqueta }}</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $valor }}</dd>
                        </div>
                    @endif
                @endforeach
            </dl>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h3 class="panel-title">
                    <x-ui.icon name="file-text" class="h-4 w-4 text-blue-600" />
                    Justificación
                </h3>
            </div>
            <div class="px-6 py-4 text-[13px] leading-relaxed text-slate-700">{{ $solicitud->justification }}</div>
            @if ($solicitud->admin_observation)
                <div class="border-t border-slate-100 px-6 py-4">
                    <h4 class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Observación del soporte técnico</h4>
                    <p class="mt-2 text-[13px] leading-relaxed text-slate-700">{{ $solicitud->admin_observation }}</p>
                </div>
            @endif
        </div>
    </div>

    @if ($solicitud->status === 'aprobada' && $solicitud->items->isNotEmpty())
        <div class="panel mt-6">
            <div class="panel-head">
                <h3 class="panel-title">
                    <x-ui.icon name="box" class="h-4 w-4 text-blue-600" />
                    Equipos asignados
                </h3>
                <span class="text-[11px] font-medium uppercase tracking-wide text-slate-400">{{ $solicitud->items->count() }} equipo(s)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-ui">
                    <thead>
                    <tr>
                        <th class="pl-6">Código patrimonial</th>
                        <th>Marca / Modelo</th>
                        <th class="pr-6">Serie</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($solicitud->items as $item)
                        <tr>
                            <td class="pl-6 font-mono text-xs text-slate-600">{{ $item->equipment->patrimonial_code }}</td>
                            <td>{{ $item->equipment->brand }} / {{ $item->equipment->model }}</td>
                            <td class="pr-6 text-slate-600">{{ $item->equipment->serial_number ?? '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
