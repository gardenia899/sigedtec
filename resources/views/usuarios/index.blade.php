<x-app-layout>
    <x-slot name="header">Usuarios</x-slot>

    <x-ui.page-header title="Gestión de usuarios" subtitle="Administra los roles de los usuarios del sistema.">
    </x-ui.page-header>

    @if (session('success'))
        <x-ui.alert type="success" class="mt-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert type="danger" class="mt-4">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="table-wrap mt-6">
        <table class="table-ui">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo electrónico</th>
                <th>Oficina</th>
                <th>Rol</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($usuarios as $usuario)
                <tr>
                    <td class="font-medium text-slate-800">{{ $usuario->name }}</td>
                    <td class="text-slate-600">{{ $usuario->email }}</td>
                    <td class="text-slate-600">{{ $usuario->office?->name ?? '—' }}</td>
                    <td>
                        <x-ui.badge :type="$usuario->role === 'admin' ? 'info' : 'neutral'"
                                    :label="$usuario->role === 'admin' ? 'Administrador' : 'Solicitante'" />
                    </td>
                    <td class="text-right">
                        @if ($usuario->id === auth()->id())
                            <span class="text-sm text-slate-400">No editable</span>
                        @else
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="link-action">Cambiar rol</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">No hay usuarios registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
