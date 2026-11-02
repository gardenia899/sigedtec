<x-app-layout>
    <x-slot name="header">Nueva solicitud</x-slot>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-800">Solicitar equipos</h2>
        <p class="text-sm text-slate-500">Completa los datos para enviar tu solicitud.</p>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('solicitudes.store') }}" class="rounded-lg border border-slate-200 bg-white shadow-sm">
            @csrf

            <div class="space-y-4 p-6">
                <div>
                    <x-input-label for="category_id" value="Categoría" />
                    <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Seleccione...</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(old('category_id') == $categoria->id)>{{ ucfirst($categoria->name) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="quantity" value="Cantidad" />
                    <x-text-input id="quantity" name="quantity" type="number" class="mt-1 block w-full" :value="old('quantity')" min="1" max="50" required />
                    <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="justification" value="Justificación" />
                    <textarea id="justification" name="justification" rows="4" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('justification') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Mínimo 10 caracteres</p>
                    <x-input-error :messages="$errors->get('justification')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4">
                <a href="{{ route('solicitudes.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Cancelar</a>
                <x-primary-button>Enviar solicitud</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
