<x-app-layout>
    <x-slot name="header">Aprobar solicitud</x-slot>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-800">Solicitud #{{ $solicitud->id }}</h2>
        <p class="text-sm text-slate-500">{{ $solicitud->office->name }} - {{ $solicitud->user->name }} | {{ ucfirst($solicitud->category->name) }} | Cantidad: {{ $solicitud->quantity }}</p>
    </div>

    @if (session('error'))
        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="mb-6 rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h3 class="font-medium text-slate-800">Justificación</h3>
        </div>
        <div class="px-6 py-4 text-sm text-slate-700">{{ $solicitud->justification }}</div>
    </div>

    <form method="POST" action="{{ route('admin.solicitudes.aprobar', $solicitud) }}">
        @csrf

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h3 class="font-medium text-slate-800">Seleccionar equipos disponibles</h3>
                <p class="text-sm text-slate-500">Selecciona exactamente {{ $solicitud->quantity }} equipo(s) de esta categoría.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-6 py-2.5">Seleccionar</th>
                        <th class="px-6 py-2.5">Código patrimonial</th>
                        <th class="px-6 py-2.5">Marca / Modelo</th>
                        <th class="px-6 py-2.5">Serie</th>
                        <th class="px-6 py-2.5">Especificaciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse ($equipos as $equipo)
                        <tr>
                            <td class="px-6 py-3">
                                <input type="checkbox" name="equipos[]" value="{{ $equipo->id }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-3 font-mono text-xs text-slate-700">{{ $equipo->patrimonial_code }}</td>
                            <td class="px-6 py-3 text-slate-700">{{ $equipo->brand }} / {{ $equipo->model }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $equipo->serial_number ?? '—' }}</td>
                            <td class="px-6 py-3 text-xs text-slate-600">
                                @if ($equipo->cpu) CPU: {{ $equipo->cpu }} | @endif
                                @if ($equipo->ram_gb) RAM: {{ $equipo->ram_gb }} GB | @endif
                                @if ($equipo->storage_gb) {{ $equipo->storage_gb }} GB {{ strtoupper($equipo->storage_type) }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                No hay equipos disponibles de esta categoría.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4">
                <a href="{{ route('admin.solicitudes.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Cancelar</a>
                <x-primary-button>Aprobar y asignar</x-primary-button>
            </div>
        </div>
    </form>
</x-app-layout>
