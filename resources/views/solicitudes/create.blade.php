<x-app-layout>
    <x-slot name="header">Nueva solicitud</x-slot>

    <x-ui.page-header title="Solicitar equipos" subtitle="Completa los datos para enviar tu solicitud al soporte técnico.">
        <a href="{{ route('solicitudes.index') }}" class="btn btn-outline">
            <x-ui.icon name="arrow-right" class="h-4 w-4 rotate-180" />
            Mis solicitudes
        </a>
    </x-ui.page-header>

    <div class="mt-6 grid max-w-4xl gap-5 lg:grid-cols-[1.6fr_1fr]">
        <form method="POST" action="{{ route('solicitudes.store') }}" class="panel">
            @csrf

            <div class="panel-head">
                <h2 class="panel-title">
                    <x-ui.icon name="clipboard" class="h-4 w-4 text-blue-600" />
                    Datos de la solicitud
                </h2>
            </div>

            <div class="space-y-5 p-6">
                <div>
                    <x-input-label for="category_id" value="Categoría *" />
                    <select id="category_id" name="category_id" required class="field-select mt-1.5">
                        <option value="">Seleccione…</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(old('category_id') == $categoria->id)>{{ ucfirst($categoria->name) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="field-error" />
                </div>

                <div>
                    <x-input-label for="quantity" value="Cantidad *" />
                    <x-text-input id="quantity" name="quantity" type="number" class="field mt-1.5" :value="old('quantity')" min="1" max="50" required />
                    <x-input-error :messages="$errors->get('quantity')" class="field-error" />
                </div>

                <div>
                    <x-input-label for="justification" value="Justificación *" />
                    <textarea id="justification" name="justification" rows="4" required class="field-area mt-1.5">{{ old('justification') }}</textarea>
                    <p class="mt-1.5 text-xs text-slate-500">Mínimo 10 caracteres.</p>
                    <x-input-error :messages="$errors->get('justification')" class="field-error" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <a href="{{ route('solicitudes.index') }}" class="btn btn-ghost">Cancelar</a>
                <button class="btn btn-primary" type="submit">
                    <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    Enviar solicitud
                </button>
            </div>
        </form>

        <aside class="space-y-4">
            <div class="panel p-5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 ring-1 ring-inset ring-blue-100">
                    <x-ui.icon name="bolt" class="h-4 w-4" />
                </span>
                <h3 class="mt-3 text-sm font-semibold text-slate-900">Antes de enviar</h3>
                <ul class="mt-2.5 space-y-2 text-[13px] leading-relaxed text-slate-600">
                    <li class="flex gap-2"><span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-slate-400"></span>La solicitud se origina en tu oficina asignada.</li>
                    <li class="flex gap-2"><span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-slate-400"></span>El soporte técnico evalúa la disponibilidad del inventario.</li>
                    <li class="flex gap-2"><span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-slate-400"></span>Puedes cancelarla mientras siga pendiente.</li>
                </ul>
            </div>
        </aside>
    </div>
</x-app-layout>
