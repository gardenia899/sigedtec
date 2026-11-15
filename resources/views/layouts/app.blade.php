<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($header) ? $header.' · '.config('app.name', 'SIGEDTEC') : config('app.name', 'SIGEDTEC') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-page font-sans text-ink antialiased">
<x-ui.loading />
@php
    $usuario = auth()->user();
    $esAdmin = $usuario?->isAdmin() ?? false;
    $activo = fn (...$rutas) => request()->routeIs(...$rutas);

    if ($esAdmin) {
        $menu = [
            ['tipo' => 'enlace', 'etiqueta' => 'Inicio', 'icono' => 'home', 'ruta' => 'dashboard', 'activo' => $activo('dashboard')],
            ['tipo' => 'enlace', 'etiqueta' => 'Inventario', 'icono' => 'box', 'ruta' => 'equipos.index', 'activo' => $activo('equipos.*')],
            ['tipo' => 'enlace', 'etiqueta' => 'Solicitudes', 'icono' => 'clipboard', 'ruta' => 'admin.solicitudes.index', 'activo' => $activo('admin.solicitudes.*')],
            ['tipo' => 'enlace', 'etiqueta' => 'Devoluciones', 'icono' => 'swap', 'ruta' => 'admin.devoluciones.index', 'activo' => $activo('admin.devoluciones.*')],
            ['tipo' => 'enlace', 'etiqueta' => 'Asignaciones', 'icono' => 'swap', 'ruta' => 'reportes.asignaciones', 'activo' => $activo('reportes.asignaciones')],
            ['tipo' => 'grupo', 'etiqueta' => 'Reportes', 'icono' => 'chart', 'abierto' => $activo('reportes.inventario', 'reportes.solicitudes', 'reportes.movimientos'), 'hijos' => [
                ['etiqueta' => 'Reporte de inventario', 'ruta' => 'reportes.inventario'],
                ['etiqueta' => 'Reporte de solicitudes', 'ruta' => 'reportes.solicitudes'],
                ['etiqueta' => 'Movimientos', 'ruta' => 'reportes.movimientos'],
            ]],
            ['tipo' => 'grupo', 'etiqueta' => 'Catálogos', 'icono' => 'folders', 'abierto' => $activo('categorias.*', 'oficinas.*'), 'hijos' => [
                ['etiqueta' => 'Categorías', 'ruta' => 'categorias.index'],
                ['etiqueta' => 'Oficinas', 'ruta' => 'oficinas.index'],
            ]],
            ['tipo' => 'enlace', 'etiqueta' => 'Usuarios', 'icono' => 'users', 'ruta' => 'usuarios.index', 'activo' => $activo('usuarios.*')],
        ];
    } else {
        $menu = [
            ['tipo' => 'enlace', 'etiqueta' => 'Inicio', 'icono' => 'home', 'ruta' => 'dashboard', 'activo' => $activo('dashboard')],
            ['tipo' => 'enlace', 'etiqueta' => 'Mis solicitudes', 'icono' => 'clipboard', 'ruta' => 'solicitudes.index', 'activo' => $activo('solicitudes.index', 'solicitudes.show')],
        ];
    }
@endphp

<div class="min-h-screen" x-data="{ menuAbierto: false }">

    {{-- ══ Barra lateral (SDD §20.4) ══ --}}
    <aside class="fixed inset-y-0 left-0 z-40 flex w-[264px] -translate-x-full flex-col bg-[linear-gradient(180deg,#061020_0%,#081A34_55%,#05101F_100%)] transition-transform duration-200 ease-out lg:translate-x-0"
           :class="menuAbierto ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        {{-- Identidad --}}
        <div class="flex items-center gap-3 border-b border-white/[0.07] px-5 py-5">
            <x-ui.icon name="logo" class="h-9 w-9 shrink-0 text-sky-400" />
            <div class="min-w-0">
                <p class="text-xl font-extrabold leading-none tracking-tight text-white">Siged<span class="text-sky-400">Tec</span></p>
                <p class="mt-1 text-[9.5px] leading-tight text-slate-400">
                    Sistema de Gestión de Inventario<br>y Despacho Tecnológico
                </p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4" aria-label="Navegación principal">
            @foreach ($menu as $item)
                @if ($item['tipo'] === 'enlace')
                    <a href="{{ route($item['ruta']) }}"
                       @class(['nav-item', 'nav-item-active' => $item['activo']])
                       @if ($item['activo']) aria-current="page" @endif>
                        <x-ui.icon :name="$item['icono']" class="h-5 w-5 shrink-0" />
                        <span class="truncate">{{ $item['etiqueta'] }}</span>
                        <x-ui.icon name="chevron-right" class="ms-auto h-3.5 w-3.5 opacity-50" />
                    </a>
                @else
                    <div x-data="{ abierto: {{ $item['abierto'] ? 'true' : 'false' }} }">
                        <button type="button" @click="abierto = ! abierto"
                                class="nav-item w-full justify-start"
                                :aria-expanded="abierto">
                            <x-ui.icon :name="$item['icono']" class="h-5 w-5 shrink-0" />
                            <span class="truncate">{{ $item['etiqueta'] }}</span>
                            <x-ui.icon name="chevron-down"
                                       class="ms-auto h-3.5 w-3.5 shrink-0 opacity-60 transition-transform"
                                       x-bind:class="abierto ? 'rotate-180' : ''" />
                        </button>
                        <div x-show="abierto" x-cloak class="mt-1 space-y-0.5">
                            @foreach ($item['hijos'] as $hijo)
                                <a href="{{ route($hijo['ruta']) }}"
                                   @class(['nav-subitem', 'nav-subitem-active' => $activo($hijo['ruta'])])>
                                    <span class="h-1 w-1 rounded-full bg-current opacity-70"></span>
                                    {{ $hijo['etiqueta'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </nav>

        {{-- Pie de la barra lateral --}}
        @if (!request()->routeIs('dashboard'))
            <div class="border-t border-white/[0.07] px-4 py-4">
                <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 text-center">
                    <x-ui.icon name="monitor" class="mx-auto h-8 w-8 text-sky-300/80" />
                    <p class="mt-2.5 text-[10.5px] italic leading-snug text-slate-400">
                        “La tecnología<br>también es un recurso<br>institucional”
                    </p>
                </div>

                <div class="mt-3 space-y-1.5 text-[10.5px] text-slate-500">
                    <p class="flex items-center gap-2 px-1"><x-ui.icon name="settings" class="h-3.5 w-3.5" />SIGEDTEC v1.0</p>
                    <p class="flex items-center gap-2 px-1"><x-ui.icon name="layers" class="h-3.5 w-3.5" />Laravel + PostgreSQL</p>
                </div>
            </div>
        @endif
    </aside>

    {{-- Fondo para cerrar el menú en móvil --}}
    <div class="fixed inset-0 z-30 bg-navy-950/60 backdrop-blur-sm lg:hidden" x-show="menuAbierto" x-cloak @click="menuAbierto = false"></div>

    {{-- ══ Columna principal ══ --}}
    <div class="flex min-h-screen min-w-0 flex-col lg:pl-[264px]">

        {{-- Barra superior --}}
        <header class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-3 border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-7">
            <button type="button" class="rounded-lg p-2 text-slate-600 transition hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 lg:hidden"
                    @click="menuAbierto = ! menuAbierto" aria-label="Abrir menú de navegación">
                <x-ui.icon name="menu" class="h-5 w-5" />
            </button>

            <p class="hidden max-w-[190px] truncate text-sm font-semibold text-slate-700 xl:block">
                {{ $header ?? 'Panel principal' }}
            </p>

            {{-- Búsqueda global (usa el filtro real del inventario) --}}
            @if ($esAdmin)
                <form method="GET" action="{{ route('equipos.index') }}"
                      class="relative min-w-0 flex-1 sm:max-w-[420px] xl:mx-4" role="search">
                    <x-ui.icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input type="search" name="q" value="{{ request('q') }}"
                           class="h-10 w-full rounded-full border border-slate-200 bg-slate-50 pl-10 pr-4 text-[13px] text-slate-700 placeholder:text-slate-400 shadow-none transition focus:border-primary-400 focus:bg-white focus:ring-2 focus:ring-primary-500/20"
                           placeholder="Buscar equipos, solicitudes, oficinas…"
                           aria-label="Buscar en el inventario">
                </form>
            @endif

            <div class="ms-auto flex items-center gap-1.5 sm:gap-3">
                {{-- Notificaciones --}}
                <div class="relative" x-data="{ abierto: false }" @click.outside="abierto = false">
                    <button type="button" @click="abierto = ! abierto"
                            class="relative rounded-full p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                            aria-label="Notificaciones de solicitudes" :aria-expanded="abierto">
                        <x-ui.icon name="bell" class="h-5 w-5" />
                        @if ($esAdmin && ($navPendientes ?? 0) > 0)
                            <span class="absolute -right-1 -top-1 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white ring-2 ring-white">
                                {{ $navPendientes > 9 ? '9+' : $navPendientes }}
                            </span>
                        @endif
                    </button>
                    <div x-show="abierto" x-cloak x-transition.origin.top.right
                         class="absolute right-0 z-50 mt-2 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
                        <div class="border-b border-slate-100 px-4 py-3">
                            <p class="text-sm font-semibold text-slate-800">{{ $esAdmin ? 'Solicitudes pendientes' : 'Mis solicitudes recientes' }}</p>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse ($navNotificaciones as $notificacion)
                                @php
                                    $enlaceNotificacion = $esAdmin
                                        ? route('admin.solicitudes.form', $notificacion)
                                        : route('solicitudes.show', $notificacion);
                                @endphp
                                <a href="{{ $enlaceNotificacion }}" class="block border-b border-slate-50 px-4 py-3 transition hover:bg-slate-50">
                                    <span class="flex items-center justify-between gap-3">
                                        <span class="text-xs font-semibold text-slate-800">Solicitud #{{ str_pad((string) $notificacion->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[10px] font-medium uppercase text-slate-500">{{ ucfirst($notificacion->status) }}</span>
                                    </span>
                                    <span class="mt-1 block truncate text-xs text-slate-600">
                                        {{ ucfirst($notificacion->category->name ?? 'Categoría') }} · {{ $notificacion->quantity }} equipo(s)
                                    </span>
                                    <span class="mt-1 block text-[10px] text-slate-400">{{ $notificacion->created_at->format('d/m/Y H:i') }}</span>
                                </a>
                            @empty
                                <p class="px-4 py-6 text-center text-xs text-slate-500">No hay solicitudes para mostrar.</p>
                            @endforelse
                        </div>
                        <a href="{{ $esAdmin ? route('admin.solicitudes.index') : route('solicitudes.index') }}"
                           class="block bg-slate-50 px-4 py-3 text-center text-xs font-semibold text-primary-700 hover:bg-primary-50">
                            Ver todas las solicitudes
                        </a>
                    </div>
                </div>

                <span class="hidden h-8 w-px bg-slate-200 sm:block"></span>

                {{-- Menú del usuario --}}
                <div class="relative" x-data="{ abierto: false }" @click.outside="abierto = false">
                    <button type="button" @click="abierto = ! abierto"
                            class="flex items-center gap-2.5 rounded-full py-1 pl-1 pr-2 transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                            :aria-expanded="abierto">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-navy-900 text-sm font-bold text-sky-300">
                            {{ strtoupper(substr($usuario->name ?? 'U', 0, 1)) }}
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-[13px] font-semibold leading-tight text-slate-800">{{ $usuario->name }}</span>
                            <span class="block text-[11px] leading-tight text-slate-500">
                                {{ $esAdmin ? 'Administrador (Soporte Técnico)' : 'Solicitante (Jefe de Oficina)' }}
                            </span>
                        </span>
                        <x-ui.icon name="chevron-down" class="h-4 w-4 text-slate-400" />
                    </button>

                    <div x-show="abierto" x-cloak x-transition.origin.top.right
                         class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                        <p class="px-3 py-2 text-[11px] text-slate-500">{{ $usuario->email }}</p>
                        <div class="my-1 h-px bg-slate-100"></div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50">
                            <x-ui.icon name="user" class="h-4 w-4" />Mi perfil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-[13px] font-medium text-rose-600 transition hover:bg-rose-50">
                                <x-ui.icon name="logout" class="h-4 w-4" />Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Contenido --}}
        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-7">
            <div class="mx-auto w-full max-w-[1280px] space-y-6">

                {{-- Notificaciones (SDD §20.4) --}}
                @if (session('success'))
                    <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
                @endif
                @if (session('error'))
                    <x-ui.alert type="danger">{{ session('error') }}</x-ui.alert>
                @endif
                @if ($errors->any())
                    <x-ui.alert type="danger" title="Revise los datos ingresados">
                        <ul class="list-disc space-y-1 ps-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                {{ $slot }}
            </div>
        </main>

        {{-- Pie --}}
        <footer class="border-t border-slate-200 bg-white px-4 py-4 sm:px-6 lg:px-7">
            <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-1.5 text-[11px] text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <p>Sistema de Gestión de Inventario y Despacho Tecnológico (SIGEDTEC)</p>
                <p class="flex items-center gap-2">
                    <span>Universidad Politécnica Territorial</span>
                    <span class="text-slate-300">|</span>
                    <span>Trayecto III · PNF en Informática</span>
                </p>
            </div>
        </footer>
    </div>
</div>
</body>
</html>
