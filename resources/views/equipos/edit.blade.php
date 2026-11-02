<x-app-layout>
    <x-slot name="header">Inventario</x-slot>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-800">Editar equipo</h2>
        <p class="text-sm text-slate-500">{{ $equipo->patrimonial_code }}</p>
    </div>

    <div class="max-w-4xl">
        <form method="POST" action="{{ route('equipos.update', $equipo) }}" class="rounded-lg border border-slate-200 bg-white shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-4 p-6 md:grid-cols-2">
                <div>
                    <x-input-label for="patrimonial_code" value="Código patrimonial" />
                    <x-text-input id="patrimonial_code" name="patrimonial_code" type="text" class="mt-1 block w-full" :value="old('patrimonial_code', $equipo->patrimonial_code)" required maxlength="20" />
                    <x-input-error :messages="$errors->get('patrimonial_code')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="category_id" value="Categoría" />
                    <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(old('category_id', $equipo->category_id) == $categoria->id)>{{ ucfirst($categoria->name) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="brand" value="Marca" />
                    <x-text-input id="brand" name="brand" type="text" class="mt-1 block w-full" :value="old('brand', $equipo->brand)" required maxlength="80" />
                    <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="model" value="Modelo" />
                    <x-text-input id="model" name="model" type="text" class="mt-1 block w-full" :value="old('model', $equipo->model)" required maxlength="80" />
                    <x-input-error :messages="$errors->get('model')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="serial_number" value="Número de serie" />
                    <x-text-input id="serial_number" name="serial_number" type="text" class="mt-1 block w-full" :value="old('serial_number', $equipo->serial_number)" maxlength="80" />
                    <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="cpu" value="Procesador" />
                    <x-text-input id="cpu" name="cpu" type="text" class="mt-1 block w-full" :value="old('cpu', $equipo->cpu)" maxlength="120" />
                    <x-input-error :messages="$errors->get('cpu')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="ram_gb" value="RAM (GB)" />
                    <x-text-input id="ram_gb" name="ram_gb" type="number" class="mt-1 block w-full" :value="old('ram_gb', $equipo->ram_gb)" min="1" />
                    <x-input-error :messages="$errors->get('ram_gb')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="storage_gb" value="Almacenamiento (GB)" />
                    <x-text-input id="storage_gb" name="storage_gb" type="number" class="mt-1 block w-full" :value="old('storage_gb', $equipo->storage_gb)" min="1" />
                    <x-input-error :messages="$errors->get('storage_gb')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="storage_type" value="Tipo de almacenamiento" />
                    <select id="storage_type" name="storage_type" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Seleccione...</option>
                        <option value="hdd" @selected(old('storage_type', $equipo->storage_type) == 'hdd')>HDD</option>
                        <option value="ssd" @selected(old('storage_type', $equipo->storage_type) == 'ssd')>SSD</option>
                        <option value="nvme" @selected(old('storage_type', $equipo->storage_type) == 'nvme')>NVMe</option>
                        <option value="emmc" @selected(old('storage_type', $equipo->storage_type) == 'emmc')>eMMC</option>
                    </select>
                    <x-input-error :messages="$errors->get('storage_type')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="acquisition_date" value="Fecha de adquisición" />
                    <x-text-input id="acquisition_date" name="acquisition_date" type="date" class="mt-1 block w-full" :value="old('acquisition_date', $equipo->acquisition_date)" />
                    <x-input-error :messages="$errors->get('acquisition_date')" class="mt-2" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="observations" value="Observaciones" />
                    <textarea id="observations" name="observations" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observations', $equipo->observations) }}</textarea>
                    <x-input-error :messages="$errors->get('observations')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4">
                <a href="{{ route('equipos.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Cancelar</a>
                <x-primary-button>Guardar cambios</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
