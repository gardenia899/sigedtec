<x-app-layout>
    <x-slot name="header">Categorías</x-slot>

    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Categorías de equipos</h2>
            <p class="text-sm text-slate-500">Clasificación para el inventario.</p>
        </div>
        <a href="{{ route('categorias.create') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Nueva categoría</a>
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
                <th class="px-4 py-2.5">Nombre</th>
                <th class="px-4 py-2.5">Descripción</th>
                <th class="px-4 py-2.5 text-center">Equipos</th>
                <th class="px-4 py-2.5">Estado</th>
                <th class="px-4 py-2.5 text-right">Acciones</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($categorias as $categoria)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-800 capitalize">{{ $categoria->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $categoria->description ?? '—' }}</td>
                    <td class="px-4 py-3 text-center text-slate-600">{{ $categoria->equipments_count }}</td>
                    <td class="px-4 py-3">
                        <x-ui.badge :type="$categoria->is_active ? 'success' : 'neutral'"
                                    :label="$categoria->is_active ? 'Activa' : 'Inactiva'" />
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('categorias.edit', $categoria) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Editar</a>
                            <form method="POST" action="{{ route('categorias.toggle', $categoria) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-sm text-slate-600 hover:text-slate-800" type="submit">
                                    {{ $categoria->is_active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">
                        No hay categorías registradas.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
