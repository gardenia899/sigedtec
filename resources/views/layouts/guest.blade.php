<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' · '.config('app.name', 'SIGEDTEC') : 'Iniciar sesión · '.config('app.name', 'SIGEDTEC') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#040D1C] font-sans text-slate-100 antialiased">
<div class="relative flex min-h-screen flex-col overflow-hidden">

    {{-- Fondo: malla azul institucional (SDD §20.2) --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_12%_8%,#123A6B_0%,transparent_55%),radial-gradient(circle_at_88%_18%,#10325F_0%,transparent_45%),linear-gradient(160deg,#050F22_0%,#06152C_48%,#04101F_100%)]"></div>
        <div class="absolute -left-44 top-1/4 h-[520px] w-[520px] rotate-12 rounded-[90px] bg-white/[0.025]"></div>
        <div class="absolute -right-28 -top-40 h-[460px] w-[460px] rotate-12 rounded-[90px] bg-sky-500/[0.07]"></div>
        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-[#030B17] to-transparent"></div>
    </div>

    <div class="relative z-10 mx-auto flex w-full max-w-[1480px] flex-1 flex-col px-5 py-6 sm:px-8 lg:px-12 lg:py-8">

        {{-- Identidad SIGEDTEC --}}
        <header>
            <a href="{{ url('/') }}" class="inline-flex flex-wrap items-center gap-3 sm:gap-4">
                <x-ui.icon name="logo" class="h-11 w-11 shrink-0 text-sky-400 sm:h-12 sm:w-12" />
                <span class="text-[26px] font-extrabold leading-none tracking-tight text-white sm:text-3xl">
                    Siged<span class="text-sky-400">Tec</span>
                </span>
                <span class="hidden h-10 w-px bg-white/15 sm:block"></span>
                <span class="hidden max-w-[230px] text-[13px] leading-snug text-slate-300 sm:block">
                    Sistema de Gestión de Inventario<br>y Despacho Tecnológico
                </span>
                <span class="w-full text-[11px] leading-snug text-slate-400 sm:hidden">
                    Sistema de Gestión de Inventario y Despacho Tecnológico
                </span>
            </a>
        </header>

        <div class="mt-8 grid flex-1 items-start gap-10 lg:mt-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,470px)] lg:gap-10 xl:gap-16">

            {{-- Columna de presentación (oculta en móvil) --}}
            <section class="hidden lg:block">
                <h1 class="mt-2 text-[40px] font-bold leading-[1.12] tracking-tight text-white xl:text-[46px]">
                    Control, organización<br>
                    <span class="text-sky-400">y eficiencia tecnológica</span>
                </h1>

                <p class="mt-5 max-w-md text-[15px] leading-relaxed text-slate-300">
                    Gestiona el inventario de equipos informáticos, solicitudes de manera
                    eficiente y realiza el despacho de forma segura y trazable.
                </p>

                <div class="mt-7 flex flex-wrap items-center gap-3 text-[13px] font-medium text-slate-200">
                    <span class="inline-flex items-center gap-2"><x-ui.icon name="shield" class="h-5 w-5 text-sky-400" />Seguro</span>
                    <span class="h-4 w-px bg-white/20"></span>
                    <span class="inline-flex items-center gap-2"><x-ui.icon name="bolt" class="h-5 w-5 text-sky-400" />Rápido</span>
                    <span class="h-4 w-px bg-white/20"></span>
                    <span class="inline-flex items-center gap-2"><x-ui.icon name="layers" class="h-5 w-5 text-sky-400" />Confiable</span>
                </div>

                <x-ui.tech-scene class="mt-4 w-full max-w-[540px]" />

                <p class="mt-1 max-w-[250px] text-[13px] italic leading-snug text-sky-300">
                    “La tecnología<br>también es un recurso institucional”
                </p>
            </section>

            {{-- Tarjeta de autenticación --}}
            <section class="w-full">
                <div class="mx-auto w-full max-w-[460px] rounded-[26px] border border-sky-400/25 bg-gradient-to-b from-[#0B1E3B]/95 to-[#061426]/95 p-6 shadow-panel backdrop-blur sm:p-8">

                    <div class="flex flex-col items-center text-center">
                        <div class="flex items-center gap-3">
                            <x-ui.icon name="logo" class="h-10 w-10 text-sky-400" />
                            <span class="text-2xl font-extrabold leading-none tracking-tight text-white">
                                Siged<span class="text-sky-400">Tec</span>
                            </span>
                        </div>
                        <p class="mt-2.5 text-[11px] leading-snug text-slate-400">
                            Sistema de Gestión de Inventario<br>y Despacho Tecnológico
                        </p>
                    </div>

                    <div class="mt-7">
                        {{ $slot }}
                    </div>
                </div>
            </section>
        </div>

        {{-- Pie institucional --}}
        <footer class="mt-8 border-t border-white/10 pt-5">
            <div class="flex flex-col gap-4 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <x-ui.icon name="bank" class="h-9 w-9 shrink-0 text-sky-400" />
                    <p class="leading-snug">
                        <span class="text-slate-300">PNF en Informática</span>
                        <span class="mx-1.5 text-white/25">|</span> Trayecto III<br>
                        Universidad Politécnica Territorial
                    </p>
                </div>
                <p class="flex items-center gap-3 text-[11px]">
                    <span class="hidden h-px w-10 bg-white/20 sm:block"></span>
                    Tecnología al servicio de la institución
                    <span class="hidden h-px w-10 bg-white/20 sm:block"></span>
                </p>
            </div>
        </footer>
    </div>
</div>
</body>
</html>
