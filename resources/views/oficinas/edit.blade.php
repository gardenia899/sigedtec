<x-app-layout>
    <x-slot name="header">Oficinas</x-slot>

    <x-ui.page-header title="Editar oficina" :subtitle="$office->code.' · '.$office->name">
        <a href="{{ route('oficinas.index') }}" class="btn btn-outline">
            <x-ui.icon name="arrow-right" class="h-4 w-4 rotate-180" />
            Volver al catálogo
        </a>
    </x-ui.page-header>

    <div class="mt-6 max-w-2xl">
        <form method="POST" action="{{ route('oficinas.update', $office) }}" class="panel">
            @csrf
            @method('PUT')

            <div class="panel-head">
                <h2 class="panel-title">
                    <x-ui.icon name="building" class="h-4 w-4 text-blue-600" />
                    Datos de la oficina
                </h2>
            </div>

            <div class="space-y-5 p-6">
                <div>
                    <x-input-label for="code" value="Código" />
                    <x-text-input id="code" name="code" type="text" class="field mt-1.5" :value="old('code', $office->code)" required maxlength="10" />
                    <x-input-error :messages="$errors->get('code')" class="field-error" />
                </div>

                <div>
                    <x-input-label for="name" value="Nombre" />
                    <x-text-input id="name" name="name" type="text" class="field mt-1.5" :value="old('name', $office->name)" required maxlength="120" />
                    <x-input-error :messages="$errors->get('name')" class="field-error" />
                </div>

                <div>
                    <x-input-label for="location" value="Ubicación" />
                    <x-text-input id="location" name="location" type="text" class="field mt-1.5" :value="old('location', $office->location)" maxlength="160" />
                    <x-input-error :messages="$errors->get('location')" class="field-error" />
                </div>

                <label class="flex cursor-pointer items-center gap-2.5">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $office->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-[13px] text-slate-700">Oficina activa</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <a href="{{ route('oficinas.index') }}" class="btn btn-ghost">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </form>
    </div>
</x-app-layout>
