<x-app-layout>
    <x-slot name="header">Aprobar solicitud</x-slot>

    <x-ui.page-header :title="'Aprobar solicitud #'.str_pad($solicitud->id, 4, '0', STR_PAD_LEFT)"
                      :subtitle="$solicitud->office->name.' · '.$solicitud->user->name.' · '.ucfirst($solicitud->category->name).' · '.$solicitud->quantity.' equipo(s)'">
        <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-outline">
            <x-ui.icon name="arrow-right" class="h-4 w-4 rotate-180" />
            Volver a la bandeja
        </a>
    </x-ui.page-header>

    @if (session('error'))
        <x-ui.alert type="danger" class="mt-4">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="panel mt-6">
        <div class="panel-head">
            <h3 class="panel-title">
                <x-ui.icon name="file-text" class="h-4 w-4 text-blue-600" />
                Justificación del solicitante
            </h3>
        </div>
        <div class="px-6 py-4 text-[13px] leading-relaxed text-slate-700">{{ $solicitud->justification }}</div>
    </div>

    <form method="POST" action="{{ route('admin.solicitudes.aprobar', $solicitud) }}" class="mt-5">
        @csrf

        <div class="panel">
            <div class="panel-head">
                <div class="min-w-0">
                    <h3 class="panel-title">
                        <x-ui.icon name="box" class="h-4 w-4 text-blue-600" />
                        Seleccionar equipos disponibles
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">Selecciona exactamente {{ $solicitud->quantity }} equipo(s) de esta categoría.</p>
                </div>
                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700 ring-1 ring-inset ring-blue-100">
                    Requeridos: {{ $solicitud->quantity }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="table-ui">
                    <thead>
                    <tr>
                        <th class="pl-6">Seleccionar</th>
                        <th>Código patrimonial</th>
                        <th>Marca / Modelo</th>
                        <th>Serie</th>
                        <th class="pr-6">Especificaciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($equipos as $equipo)
                        <tr>
                            <td class="pl-6">
                                <input type="checkbox" name="equipos[]" value="{{ $equipo->id }}" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="font-mono text-xs text-slate-600">{{ $equipo->patrimonial_code }}</td>
                            <td>{{ $equipo->brand }} / {{ $equipo->model }}</td>
                            <td class="text-slate-600">{{ $equipo->serial_number ?? '—' }}</td>
                            <td class="pr-6 text-xs text-slate-600">
                                @if ($equipo->cpu) CPU: {{ $equipo->cpu }} | @endif
                                @if ($equipo->ram_gb) RAM: {{ $equipo->ram_gb }} GB | @endif
                                @if ($equipo->storage_gb) {{ $equipo->storage_gb }} GB {{ strtoupper($equipo->storage_type) }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">No hay equipos disponibles de esta categoría.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-ghost">Cancelar</a>
                <button class="btn btn-primary" type="submit">
                    <x-ui.icon name="swap" class="h-4 w-4" />
                    Aprobar y asignar
                </button>
            </div>
        </div>
    </form>
</x-app-layout>
