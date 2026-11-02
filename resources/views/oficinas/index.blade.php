<x-app-layout>
    <x-slot name="header">Oficinas</x-slot>

    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Catálogo de oficinas</h2>
            <p class="text-sm text-slate-500">Unidades institucionales receptoras de equipos.</p>
        </div>
        <a href="{{ route('oficinas.create') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Nueva oficina</a>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 rounded-md bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="mt-6 overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
            <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-2.5">Código</th>
                <th class="px-4 py-2.5">Nombre</th>
                <th class="px-4 py-2.5">Ubicación</th>
                <th class="px-4 py-2.5 text-center">Usuarios</th>
                <th class="px-4 py-2.5">Estado</th>
                <th class="px-4 py-2.5 text-right">Acciones</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($oficinas as $oficina)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $oficina->code }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $oficina->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $oficina->location ?? '—' }}</td>
                    <td class="px-4 py-3 text-center text-slate-600">{{ $oficina->users_count }}</td>
                    <td class="px-4 py-3">
                        <x-ui.badge :type="$oficina->is_active ? 'success' : 'neutral'"
                                    :label="$oficina->is_active ? 'Activa' : 'Inactiva'" />
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('oficinas.edit', $oficina) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Editar</a>
                            <form method="POST" action="{{ route('oficinas.toggle', $oficina) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-sm text-slate-600 hover:text-slate-800" type="submit">
                                    {{ $oficina->is_active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">
                        No hay oficinas registradas.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
