<x-app-layout>
    <x-slot name="header">Solicitudes</x-slot>

    <x-ui.page-header title="Bandeja de solicitudes" subtitle="Aprueba o rechaza las solicitudes de equipos del personal." />

    @php
        $pestanas = [
            'pendiente' => ['etiqueta' => 'Pendientes', 'activo' => 'bg-amber-500 text-white', 'punto' => 'bg-amber-400'],
            'aprobada' => ['etiqueta' => 'Aprobadas', 'activo' => 'bg-emerald-600 text-white', 'punto' => 'bg-emerald-400'],
            'rechazada' => ['etiqueta' => 'Rechazadas', 'activo' => 'bg-rose-600 text-white', 'punto' => 'bg-rose-400'],
            'cancelada' => ['etiqueta' => 'Canceladas', 'activo' => 'bg-slate-600 text-white', 'punto' => 'bg-slate-400'],
            'todas' => ['etiqueta' => 'Todas', 'activo' => 'bg-blue-600 text-white', 'punto' => 'bg-blue-400'],
        ];
    @endphp

    <div class="mt-5 flex flex-wrap items-center gap-2">
        @foreach ($pestanas as $clave => $pestana)
            <a href="{{ route('admin.solicitudes.index', ['estado' => $clave]) }}"
               class="inline-flex h-9 items-center gap-2 rounded-lg px-3 text-[13px] font-semibold transition
                      {{ $estado === $clave ? $pestana['activo'].' shadow-card' : 'border border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:text-slate-900' }}">
                @if ($clave !== 'todas')
                    <span class="h-1.5 w-1.5 rounded-full {{ $estado === $clave ? 'bg-white/80' : $pestana['punto'] }}" aria-hidden="true"></span>
                    {{ $pestana['etiqueta'] }} ({{ $contadores[$clave] }})
                @else
                    {{ $pestana['etiqueta'] }}
                @endif
            </a>
        @endforeach
    </div>

    @if (session('success'))
        <x-ui.alert type="success" class="mt-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert type="danger" class="mt-4">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="table-wrap mt-5">
        <table class="table-ui">
            <thead>
            <tr>
                <th>#</th>
                <th>Oficina</th>
                <th>Solicitante</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($solicitudes as $solicitud)
                <tr>
                    <td class="font-mono text-xs text-slate-500">#{{ str_pad($solicitud->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $solicitud->office->name }}</td>
                    <td>{{ $solicitud->user->name }}</td>
                    <td>{{ ucfirst($solicitud->category->name) }}</td>
                    <td>{{ $solicitud->quantity }}</td>
                    <td class="text-slate-600">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <x-ui.badge :type="$solicitud->status === 'pendiente' ? 'warning' : ($solicitud->status === 'aprobada' ? 'success' : ($solicitud->status === 'rechazada' ? 'danger' : 'neutral'))"
                                    :label="ucfirst($solicitud->status)" />
                    </td>
                    <td class="text-right">
                        @if ($solicitud->status === 'pendiente')
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('solicitudes.show', $solicitud) }}" class="link-action">Ver</a>
                                <a href="{{ route('admin.solicitudes.form', $solicitud) }}" class="text-[13px] font-medium text-emerald-600 transition hover:text-emerald-800">Aprobar</a>
                                <a href="{{ route('admin.solicitudes.formRechazar', $solicitud) }}" class="link-danger">Rechazar</a>
                            </div>
                        @else
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('solicitudes.show', $solicitud) }}" class="link-action">Ver</a>
                                <span class="text-[13px] text-slate-400">Procesada</span>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty-state">No hay solicitudes en este estado.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $solicitudes->links() }}
    </div>
</x-app-layout>
