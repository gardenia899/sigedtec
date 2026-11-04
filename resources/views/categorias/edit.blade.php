<x-app-layout>
    <x-slot name="header">Categorías</x-slot>

    <x-ui.page-header title="Editar categoría" :subtitle="ucfirst($categoria->name)">
        <a href="{{ route('categorias.index') }}" class="btn btn-outline">
            <x-ui.icon name="arrow-right" class="h-4 w-4 rotate-180" />
            Volver al catálogo
        </a>
    </x-ui.page-header>

    <div class="mt-6 max-w-2xl">
        <form method="POST" action="{{ route('categorias.update', $categoria) }}" class="panel">
            @csrf
            @method('PUT')

            <div class="panel-head">
                <h2 class="panel-title">
                    <x-ui.icon name="folders" class="h-4 w-4 text-blue-600" />
                    Datos de la categoría
                </h2>
            </div>

            <div class="space-y-5 p-6">
                <div>
                    <x-input-label for="name" value="Nombre" />
                    <x-text-input id="name" name="name" type="text" class="field mt-1.5" :value="old('name', $categoria->name)" required maxlength="80" />
                    <x-input-error :messages="$errors->get('name')" class="field-error" />
                </div>

                <div>
                    <x-input-label for="description" value="Descripción" />
                    <textarea id="description" name="description" rows="3" class="field-area mt-1.5">{{ old('description', $categoria->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="field-error" />
                </div>

                <div>
                    <span class="block text-[12.5px] font-medium text-slate-700">Especificaciones técnicas de la categoría</span>
                    <p class="mt-1 text-xs text-slate-500">
                        Marca los datos que se pedirán al registrar un equipo de esta categoría. En impresoras, proyectores o periféricos normalmente no aplica ninguno.
                    </p>
                    <div class="mt-3 grid gap-2 sm:grid-cols-2">
                        @foreach (\App\Models\Category::CAMPOS_TECNICOS as $campo => $etiqueta)
                            <label class="flex cursor-pointer items-center gap-2.5 rounded-lg border border-slate-200 px-3 py-2.5 transition hover:border-blue-300">
                                <input type="checkbox" name="spec_fields[]" value="{{ $campo }}"
                                       @checked(in_array($campo, old('spec_fields', $categoria->camposTecnicos()), true))
                                       class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-[13px] text-slate-700">{{ $etiqueta }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('spec_fields')" class="field-error" />
                </div>

                <label class="flex cursor-pointer items-center gap-2.5">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $categoria->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-[13px] text-slate-700">Categoría activa</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <a href="{{ route('categorias.index') }}" class="btn btn-ghost">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </form>
    </div>
</x-app-layout>
