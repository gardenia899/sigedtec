<x-app-layout>
    <x-slot name="header">Devoluciones</x-slot>

    <x-ui.page-header title="Devoluciones pendientes" subtitle="Revisa los equipos devueltos y confirma su estado antes de liberarlos." />

    @if (session('success'))
        <x-ui.alert type="success" class="mt-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert type="danger" class="mt-4">{{ session('error') }}</x-ui.alert>
    @endif
    @if ($errors->any())
        <x-ui.alert type="danger" class="mt-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </x-ui.alert>
    @endif

    <div class="table-wrap mt-6">
        <table class="table-ui">
            <thead>
            <tr>
                <th>Solicitud</th>
                <th>Oficina / solicitante</th>
                <th>Equipo</th>
                <th>Solicitud de devolución</th>
                <th>Confirmar estado</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($devoluciones as $devolucion)
                <tr>
                    <td>
                        <a href="{{ route('solicitudes.show', $devolucion->request) }}" class="link-action">
                            #{{ str_pad($devolucion->request_id, 4, '0', STR_PAD_LEFT) }}
                        </a>
                    </td>
                    <td>
                        {{ $devolucion->request->office->name }}<br>
                        <span class="text-xs text-slate-500">{{ $devolucion->request->user->name }}</span>
                    </td>
                    <td>
                        <span class="font-mono text-xs">{{ $devolucion->equipment->patrimonial_code }}</span><br>
                        <span class="text-xs text-slate-500">{{ $devolucion->equipment->brand }} / {{ $devolucion->equipment->model }}</span>
                    </td>
                    <td>
                        {{ $devolucion->return_requested_at->format('d/m/Y H:i') }}
                        @if ($devolucion->return_request_observations)
                            <p class="mt-1 max-w-xs whitespace-normal text-xs text-slate-500">{{ $devolucion->return_request_observations }}</p>
                        @endif
                    </td>
                    <td class="min-w-64">
                        <form method="POST" action="{{ route('admin.devoluciones.confirmar', $devolucion) }}" class="space-y-2">
                            @csrf
                            <select name="return_condition" required class="field-select">
                                <option value="operativo">Operativo → Disponible</option>
                                <option value="requiere_mantenimiento">Requiere mantenimiento → Mantenimiento</option>
                            </select>
                            <textarea name="return_observations" rows="2" maxlength="255"
                                      placeholder="Observaciones de revisión (opcional)" class="field"></textarea>
                            <button class="btn btn-primary btn-sm">Confirmar devolución</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">No hay devoluciones pendientes de revisión.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $devoluciones->links() }}
    </div>
</x-app-layout>
