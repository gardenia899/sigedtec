<x-app-layout>
    <x-slot name="header">Categorías</x-slot>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-800">Editar categoría</h2>
        <p class="text-sm text-slate-500 capitalize">{{ $categoria->name }}</p>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('categorias.update', $categoria) }}" class="rounded-lg border border-slate-200 bg-white shadow-sm">
            @csrf
            @method('PUT')

            <div class="space-y-4 p-6">
                <div>
                    <x-input-label for="name" value="Nombre" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $categoria->name)" required maxlength="80" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Descripción" />
                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $categoria->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $categoria->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-slate-700">Activa</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4">
                <a href="{{ route('categorias.index') }}" class="text-sm text-slate-600 hover:text-ssla-800">Cancelar</a>
                <x-primary-button>Guardar cambios</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
