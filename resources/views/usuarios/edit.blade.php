<x-app-layout>
    <x-slot name="header">Usuarios</x-slot>

    <x-ui.page-header title="Cambiar rol de usuario" subtitle="Selecciona el nuevo rol para {{ $usuario->name }}.">
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
            <x-ui.icon name="arrow-left" class="h-4 w-4" />
            Volver al listado
        </a>
    </x-ui.page-header>

    <div class="mt-6 max-w-2xl">
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Información del usuario</h2>
                <p class="mt-1 text-sm text-slate-500">Oficina: {{ $usuario->office?->name ?? 'Sin asignar' }} · Correo: {{ $usuario->email }}</p>
            </div>

            <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="px-6 py-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="role" class="form-label">Rol</label>
                    <select id="role" name="role" class="form-input">
                        <option value="solicitante" @selected(old('role', $usuario->role) === 'solicitante')>Solicitante (Jefe de Oficina)</option>
                        <option value="admin" @selected(old('role', $usuario->role) === 'admin')>Administrador (Soporte Técnico)</option>
                    </select>
                    @error('role')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
