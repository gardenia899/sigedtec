<x-app-layout>
    <x-slot name="header">Inventario</x-slot>

    <x-ui.page-header title="Nuevo equipo" subtitle="Registrar un bien en el inventario institucional.">
        <a href="{{ route('equipos.index') }}" class="btn btn-outline">
            <x-ui.icon name="arrow-right" class="h-4 w-4 rotate-180" />
            Volver al inventario
        </a>
    </x-ui.page-header>

    @php
        $mapaCampos = $categorias->mapWithKeys(fn ($c) => [(string) $c->id => $c->camposTecnicos()])->all();
    @endphp

    <div class="mt-6 max-w-4xl">
        <form method="POST" action="{{ route('equipos.store') }}" class="panel"
              x-data="{ cat: '{{ old('category_id') }}', campos: {{ Js::from($mapaCampos) }}, aplica(campo) { return (this.campos[this.cat] || []).includes(campo) } }">
            @csrf

            <div class="panel-head">
                <h2 class="panel-title">
                    <x-ui.icon name="box" class="h-4 w-4 text-blue-600" />
                    Datos del equipo
                </h2>
                <span class="text-[11px] font-medium uppercase tracking-wide text-slate-400">Los campos con * son obligatorios</span>
            </div>

            <div class="grid gap-5 p-6 md:grid-cols-2">
                <div>
                    <x-input-label for="patrimonial_code" value="Código patrimonial *" />
                    <x-text-input id="patrimonial_code" name="patrimonial_code" type="text" class="field mt-1.5" :value="old('patrimonial_code')" required maxlength="20" />
                    <x-input-error :messages="$errors->get('patrimonial_code')" class="field-error" />
                </div>
                <div>
                    <x-input-label for="category_id" value="Categoría *" />
                    <select id="category_id" name="category_id" required class="field-select mt-1.5" x-model="cat">
                        <option value="">Seleccione…</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(old('category_id') == $categoria->id)>{{ ucfirst($categoria->name) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="field-error" />
                </div>
                <div>
                    <x-input-label for="brand" value="Marca *" />
                    <x-text-input id="brand" name="brand" type="text" class="field mt-1.5" :value="old('brand')" required maxlength="80" />
                    <x-input-error :messages="$errors->get('brand')" class="field-error" />
                </div>
                <div>
                    <x-input-label for="model" value="Modelo *" />
                    <x-text-input id="model" name="model" type="text" class="field mt-1.5" :value="old('model')" required maxlength="80" />
                    <x-input-error :messages="$errors->get('model')" class="field-error" />
                </div>
                <div>
                    <x-input-label for="serial_number" value="Número de serie" />
                    <x-text-input id="serial_number" name="serial_number" type="text" class="field mt-1.5" :value="old('serial_number')" maxlength="80" />
                    <x-input-error :messages="$errors->get('serial_number')" class="field-error" />
                </div>
                <div>
                    <x-input-label for="status" value="Estado *" />
                    <select id="status" name="status" required class="field-select mt-1.5">
                        <option value="disponible" @selected(old('status') == 'disponible')>Disponible</option>
                        <option value="mantenimiento" @selected(old('status') == 'mantenimiento')>En mantenimiento</option>
                        <option value="asignado" @selected(old('status') == 'asignado')>Asignado</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="field-error" />
                </div>
            </div>

            <div class="panel-head border-t border-slate-100">
                <h2 class="panel-title">
                    <x-ui.icon name="wrench" class="h-4 w-4 text-blue-600" />
                    Especificaciones técnicas
                </h2>
            </div>

            <div class="grid gap-5 p-6 md:grid-cols-2">
                <div x-show="aplica('cpu')" x-cloak>
                    <x-input-label for="cpu" value="Procesador" />
                    <x-text-input id="cpu" name="cpu" type="text" class="field mt-1.5" :value="old('cpu')" maxlength="120" />
                    <x-input-error :messages="$errors->get('cpu')" class="field-error" />
                </div>
                <div x-show="aplica('ram_gb')" x-cloak>
                    <x-input-label for="ram_gb" value="RAM (GB)" />
                    <x-text-input id="ram_gb" name="ram_gb" type="number" class="field mt-1.5" :value="old('ram_gb')" min="1" />
                    <x-input-error :messages="$errors->get('ram_gb')" class="field-error" />
                </div>
                <div x-show="aplica('storage_gb')" x-cloak>
                    <x-input-label for="storage_gb" value="Almacenamiento (GB)" />
                    <x-text-input id="storage_gb" name="storage_gb" type="number" class="field mt-1.5" :value="old('storage_gb')" min="1" />
                    <x-input-error :messages="$errors->get('storage_gb')" class="field-error" />
                </div>
                <div x-show="aplica('storage_type')" x-cloak>
                    <x-input-label for="storage_type" value="Tipo de almacenamiento" />
                    <select id="storage_type" name="storage_type" class="field-select mt-1.5">
                        <option value="">Seleccione…</option>
                        <option value="hdd" @selected(old('storage_type') == 'hdd')>HDD</option>
                        <option value="ssd" @selected(old('storage_type') == 'ssd')>SSD</option>
                        <option value="nvme" @selected(old('storage_type') == 'nvme')>NVMe</option>
                        <option value="emmc" @selected(old('storage_type') == 'emmc')>eMMC</option>
                    </select>
                    <x-input-error :messages="$errors->get('storage_type')" class="field-error" />
                </div>
                <p class="rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3 text-[13px] text-slate-600 md:col-span-2"
                   x-show="! cat" x-cloak>
                    Selecciona una categoría para ver las especificaciones técnicas que le corresponden.
                </p>
                <p class="rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3 text-[13px] text-slate-600 md:col-span-2"
                   x-show="cat && (campos[cat] || []).length === 0" x-cloak>
                    Esta categoría no requiere procesador, RAM ni almacenamiento. Describe sus características en «Observaciones».
                </p>
                <div>
                    <x-input-label for="acquisition_date" value="Fecha de adquisición" />
                    <x-text-input id="acquisition_date" name="acquisition_date" type="date" class="field mt-1.5" :value="old('acquisition_date')" />
                    <x-input-error :messages="$errors->get('acquisition_date')" class="field-error" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="observations" value="Observaciones" />
                    <textarea id="observations" name="observations" rows="3" class="field-area mt-1.5">{{ old('observations') }}</textarea>
                    <x-input-error :messages="$errors->get('observations')" class="field-error" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <a href="{{ route('equipos.index') }}" class="btn btn-ghost">Cancelar</a>
                <button class="btn btn-primary" type="submit">
                    <x-ui.icon name="shield" class="h-4 w-4" />
                    Guardar equipo
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
