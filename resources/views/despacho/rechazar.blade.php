<x-app-layout>
    <x-slot name="header">Rechazar solicitud</x-slot>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-800">Solicitud #{{ $solicitud->id }}</h2>
        <p class="text-sm text-slate-500">{{ $solicitud->office->name }} - {{ $solicitud->user->name }}</p>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.solicitudes.rechazar', $solicitud) }}" class="rounded-lg border border-slate-200 bg-white shadow-sm">
            @csrf

            <div class="space-y-4 p-6">
                <div>
                    <x-input-label for="admin_observation" value="Motivo del rechazo" />
                    <textarea id="admin_observation" name="admin_observation" rows="4" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('admin_observation') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Debes indicar el motivo para rechazar esta solicitud.</p>
                    <x-input-error :messages="$errors->get('admin_observation')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4">
                <a href="{{ route('admin.solicitudes.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Cancelar</a>
                <button class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Rechazar solicitud</button>
            </div>
        </form>
    </div>
</x-app-layout>
