<x-app-layout>
    <x-slot name="header">Detalle de solicitud</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Solicitud #{{ $solicitud->id }}</h2>
            <p class="text-sm text-slate-500">Fecha: {{ $solicitud->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <a href="{{ route('solicitudes.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Volver</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h3 class="font-medium text-slate-800">Datos de la solicitud</h3>
            </div>
            <div class="space-y-3 px-6 py-4 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Oficina</span><span class="text-slate-700">{{ $solicitud->office->name }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Categoría</span><span class="text-slate-700">{{ ucfirst($solicitud->category->name) }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Cantidad</span><span class="text-slate-700">{{ $solicitud->quantity }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Estado</span><span class="text-slate-700">{{ ucfirst($solicitud->status) }}</span></div>
                @if ($solicitud->procesadaPor)
                    <div class="flex justify-between"><span class="text-slate-500">Atendida por</span><span class="text-slate-700">{{ $solicitud->procesadaPor->name }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Fecha de atención</span><span class="text-slate-700">{{ $solicitud->processed_at?->format('d/m/Y H:i') }}</span></div>
                @endif
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h3 class="font-medium text-slate-800">Justificación</h3>
            </div>
            <div class="px-6 py-4 text-sm text-slate-700">{{ $solicitud->justification }}</div>
            @if ($solicitud->admin_observation)
                <div class="border-t border-slate-200 px-6 py-4">
                    <h4 class="text-sm font-medium text-slate-800">Observación</h4>
                    <p class="mt-2 text-sm text-slate-700">{{ $solicitud->admin_observation }}</p>
                </div>
            @endif
        </div>
    </div>

    @if ($solicitud->status === 'aprobada' && $solicitud->items->isNotEmpty())
        <div class="mt-6 rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h3 class="font-medium text-slate-800">Equipos asignados</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-6 py-2.5">Código patrimonial</th>
                        <th class="px-6 py-2.5">Marca / Modelo</th>
                        <th class="px-6 py-2.5">Serie</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @foreach ($solicitud->items as $item)
                        <tr>
                            <td class="px-6 py-3 font-mono text-xs text-slate-700">{{ $item->equipment->patrimonial_code }}</td>
                            <td class="px-6 py-3 text-slate-700">{{ $item->equipment->brand }} / {{ $item->equipment->model }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $item->equipment->serial_number ?? '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
