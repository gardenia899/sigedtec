<x-app-layout>
    <x-slot name="header">Usuarios</x-slot>

    <x-ui.page-header title="Cambiar rol de usuario" :subtitle="'Selecciona el nuevo rol para '.$usuario->name.'.'">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline">
            <x-ui.icon name="arrow-left" class="h-4 w-4" />
            Volver al listado
        </a>
    </x-ui.page-header>

    <div class="mt-6 max-w-2xl">
        <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="panel">
            @csrf
            @method('PUT')

            <div class="panel-head">
                <h2 class="panel-title">
                    <x-ui.icon name="user" class="h-4 w-4 text-blue-600" />
                    Información del usuario
                </h2>
                <x-ui.badge :type="$usuario->role === 'admin' ? 'info' : 'neutral'"
                            :label="$usuario->role === 'admin' ? 'Administrador' : 'Solicitante'" />
            </div>

            <div class="space-y-5 p-6">
                <dl class="divide-y divide-slate-100 rounded-xl border border-slate-200 text-[13px]">
                    <div class="flex items-center justify-between gap-4 px-4 py-3">
                        <dt class="text-slate-500">Nombre</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $usuario->name }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 px-4 py-3">
                        <dt class="text-slate-500">Correo electrónico</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $usuario->email }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 px-4 py-3">
                        <dt class="text-slate-500">Oficina</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $usuario->office->name ?? 'Sin asignar' }}</dd>
                    </div>
                </dl>

                <div>
                    <x-input-label for="role" value="Rol" />
                    <select id="role" name="role" class="field-select mt-1.5">
                        <option value="solicitante" @selected(old('role', $usuario->role) === 'solicitante')>Solicitante (Jefe de Oficina)</option>
                        <option value="admin" @selected(old('role', $usuario->role) === 'admin')>Administrador (Soporte Técnico)</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="field-error" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <a href="{{ route('usuarios.index') }}" class="btn btn-ghost">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</x-app-layout>
