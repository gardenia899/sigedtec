<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($header) ? $header.' · '.config('app.name') : config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
<div class="min-h-screen flex" x-data="{ menuAbierto: false }">

    {{-- ══ Barra lateral (SDD §20.4) ══ --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 flex flex-col transform transition-transform duration-200 ease-out -translate-x-full lg:translate-x-0"
        :class="menuAbierto ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        <div class="h-16 flex items-center gap-2 px-5 border-b border-slate-200">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white text-sm font-bold">ST</span>
            <span class="font-semibold tracking-tight text-slate-800">SIGEDTEC</span>
        </div>

        @php
            $rol = auth()->user()->role ?? '';
            $items = [];

            if ($rol === 'admin') {
                $items = [
                    ['ruta' => 'dashboard', 'etiqueta' => 'Panel', 'visible' => true],
                    ['ruta' => 'admin.solicitudes.index', 'etiqueta' => 'Solicitudes', 'visible' => true],
                    ['ruta' => 'equipos.index', 'etiqueta' => 'Inventario', 'visible' => true],
                    ['ruta' => 'categorias.index', 'etiqueta' => 'Categorías', 'visible' => true],
                    ['ruta' => 'oficinas.index', 'etiqueta' => 'Oficinas', 'visible' => true],
                    ['ruta' => 'reportes.inventario', 'etiqueta' => 'Reportes', 'visible' => true],
                    ['ruta' => 'reportes.solicitudes', 'etiqueta' => 'Reportes solicitudes', 'visible' => true],
                    ['ruta' => 'reportes.asignaciones', 'etiqueta' => 'Asignaciones', 'visible' => true],
                    ['ruta' => 'reportes.movimientos', 'etiqueta' => 'Movimientos', 'visible' => true],
                ];
            } else {
                $items = [
                    ['ruta' => 'dashboard', 'etiqueta' => 'Panel', 'visible' => true],
                    ['ruta' => 'solicitudes.index', 'etiqueta' => 'Mis solicitudes', 'visible' => true],
                ];
            }
        @endphp

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @foreach ($items as $item)
                @if ($item['visible'])
                    <a href="{{ route($item['ruta']) }}"
                       @class([
                           'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                           'bg-blue-50 text-blue-700' => request()->routeIs(str_replace(['.index', '.create', '.edit'], ['.*', '.*', '.*'], $item['ruta'])) || request()->routeIs($item['ruta']),
                           'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! (request()->routeIs(str_replace(['.index', '.create', '.edit'], ['.*', '.*', '.*'], $item['ruta'])) || request()->routeIs($item['ruta'])),
                       ])>
                        {{ $item['etiqueta'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="border-t border-slate-200 px-4 py-3">
            <p class="text-sm font-medium text-slate-800 truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-slate-500">
                {{ auth()->user()->isAdmin() ? 'Soporte Técnico' : 'Jefe de Oficina' }} · {{ auth()->user()->office->name ?? '' }}
            </p>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Fondo para cerrar el menú en móvil --}}
    <div class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden" x-show="menuAbierto" x-cloak @click="menuAbierto = false"></div>

    {{-- ══ Columna principal ══ --}}
    <div class="flex-1 flex flex-col min-w-0 lg:ml-64">

        {{-- Barra superior --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center gap-3 px-4 lg:px-6 shrink-0">
            <button type="button" class="lg:hidden p-2 -ml-2 rounded-md text-slate-600 hover:bg-slate-100"
                    @click="menuAbierto = !menuAbierto" aria-label="Abrir menú de navegación">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="text-sm font-semibold text-slate-700 truncate">{{ $header ?? 'Sistema de Gestión de Inventario y Despacho Tecnológico' }}</h1>

            <div class="ml-auto hidden sm:flex items-center gap-2 text-xs text-slate-500">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 font-semibold text-slate-600">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                {{ auth()->user()->email }}
            </div>
        </header>

        {{-- Contenido principal --}}
        <main class="flex-1 px-4 py-6 lg:px-6 space-y-6">

            {{-- Sistema de notificaciones (SDD §20.4) --}}
            @if (session('success'))
                <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
            @endif
            @if (session('error'))
                <x-ui.alert type="danger">{{ session('error') }}</x-ui.alert>
            @endif
            @if ($errors->any())
                <x-ui.alert type="danger" title="Revise los datos ingresados">
                    <ul class="list-disc ms-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-ui.alert>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>