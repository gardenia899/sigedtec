<x-app-layout>
    <x-slot name="header">Rechazar solicitud</x-slot>

    <x-ui.page-header :title="'Rechazar solicitud #'.str_pad($solicitud->id, 4, '0', STR_PAD_LEFT)"
                      :subtitle="$solicitud->office->name.' · '.$solicitud->user->name">
        <x-ui.badge type="warning" label="Pendiente" />
        <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-outline">
            <x-ui.icon name="arrow-right" class="h-4 w-4 rotate-180" />
            Volver a la bandeja
        </a>
    </x-ui.page-header>

    <div class="mt-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.solicitudes.rechazar', $solicitud) }}" class="panel">
            @csrf

            <div class="panel-head">
                <h2 class="panel-title">
                    <x-ui.icon name="file-text" class="h-4 w-4 text-rose-600" />
                    Motivo del rechazo
                </h2>
            </div>

            <div class="space-y-4 p-6">
                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Justificación del solicitante</p>
                    <p class="mt-1.5 text-[13px] leading-relaxed text-slate-700">{{ $solicitud->justification }}</p>
                </div>

                <div>
                    <x-input-label for="admin_observation" value="Motivo del rechazo *" />
                    <textarea id="admin_observation" name="admin_observation" rows="4" required class="field-area mt-1.5">{{ old('admin_observation') }}</textarea>
                    <p class="mt-1.5 text-xs text-slate-500">Debes indicar el motivo para rechazar esta solicitud.</p>
                    <x-input-error :messages="$errors->get('admin_observation')" class="field-error" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-ghost">Cancelar</a>
                <button class="btn bg-rose-600 text-white hover:bg-rose-700" type="submit">
                    <x-ui.icon name="bell" class="h-4 w-4" />
                    Rechazar solicitud
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
