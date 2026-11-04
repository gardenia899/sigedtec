<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SIGEDTEC') }} · Gestión de equipos tecnológicos</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-page font-sans text-ink antialiased">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-[0_10px_20px_-12px_rgba(37,99,235,0.9)]">
                    <x-ui.icon name="logo" class="h-5 w-5" />
                </span>
                <span class="leading-tight">
                    <span class="block text-[15px] font-bold tracking-tight text-slate-900">SigedTec</span>
                    <span class="block text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-400">Gestión de equipos</span>
                </span>
            </a>

            <nav class="hidden items-center gap-1 md:flex" aria-label="Navegación principal">
                <a href="#inicio" class="rounded-lg px-3 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Inicio</a>
                <a href="#caracteristicas" class="rounded-lg px-3 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Características</a>
                <a href="#contacto" class="rounded-lg px-3 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Contacto</a>
            </nav>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm sm:h-10 sm:px-4 sm:text-[13px]">
                        Ir al panel
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-ghost btn-sm hidden sm:inline-flex">Crear cuenta</a>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm sm:h-10 sm:px-4 sm:text-[13px]">
                        <x-ui.icon name="login" class="h-4 w-4" />
                        Iniciar Sesión
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer id="contacto" class="bg-[#081A34] text-slate-300">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-sky-300 ring-1 ring-inset ring-white/15">
                        <x-ui.icon name="logo" class="h-5 w-5" />
                    </span>
                    <div class="leading-tight">
                        <p class="text-[15px] font-bold text-white">SigedTec</p>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-500">Gestión de equipos</p>
                    </div>
                </div>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-400">
                    Sistema de Gestión y Despacho de Equipos Tecnológicos. Centraliza el inventario,
                    las solicitudes y las asignaciones del soporte técnico institucional.
                </p>
            </div>

            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Sistema</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="#inicio" class="transition hover:text-white">Inicio</a></li>
                    <li><a href="#caracteristicas" class="transition hover:text-white">Características</a></li>
                    <li><a href="#como-funciona" class="transition hover:text-white">Cómo funciona</a></li>
                    <li><a href="{{ route('login') }}" class="transition hover:text-white">Iniciar sesión</a></li>
                </ul>
            </div>

            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Institución</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li class="flex items-start gap-2">
                        <x-ui.icon name="building" class="mt-0.5 h-4 w-4 shrink-0 text-slate-500" />
                        <span>Unidad de Soporte Técnico</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-ui.icon name="bank" class="mt-0.5 h-4 w-4 shrink-0 text-slate-500" />
                        <span>PNF en Informática · Trayecto III</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-ui.icon name="file-text" class="mt-0.5 h-4 w-4 shrink-0 text-slate-500" />
                        <span>SIGEDTEC v1.0</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-5 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <p>&copy; {{ date('Y') }} SigedTec. Todos los derechos reservados.</p>
                <p>Laravel · PostgreSQL · Tailwind CSS</p>
            </div>
        </div>
    </footer>
</body>
</html>
