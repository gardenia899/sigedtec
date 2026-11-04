<x-layouts.site>
    {{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
    <section id="inicio" class="relative overflow-hidden">
        <div class="pointer-events-none absolute -right-32 -top-40 h-[420px] w-[420px] rounded-full bg-sky-200/40 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-40 top-24 h-[360px] w-[360px] rounded-full bg-blue-100/60 blur-3xl"></div>

        <div class="relative mx-auto grid max-w-6xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:py-24">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-blue-700">
                    <x-ui.icon name="shield" class="h-3.5 w-3.5" />
                    PNF en Informática · Trayecto III
                </span>

                <h1 class="mt-5 text-4xl font-extrabold leading-[1.1] tracking-tight text-slate-900 sm:text-5xl">
                    Bienvenido a <span class="bg-gradient-to-r from-blue-600 to-sky-500 bg-clip-text text-transparent">SigedTec</span>
                </h1>

                <p class="mt-5 max-w-xl text-[15px] leading-relaxed text-slate-600">
                    Sistema de Gestión y Despacho de Equipos Tecnológicos. Administra el inventario
                    institucional, atiende las solicitudes del personal y controla cada asignación
                    con trazabilidad completa.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <x-ui.icon name="login" class="h-4 w-4" />
                        Iniciar Sesión
                    </a>
                    <a href="#caracteristicas" class="btn btn-outline">
                        Conocer características
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>

                <dl class="mt-10 grid max-w-md grid-cols-3 gap-4 border-t border-slate-200 pt-6">
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-400">Módulos</dt>
                        <dd class="mt-1 text-2xl font-bold text-slate-900">07</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-400">Roles</dt>
                        <dd class="mt-1 text-2xl font-bold text-slate-900">02</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-400">Trazabilidad</dt>
                        <dd class="mt-1 text-2xl font-bold text-slate-900">100%</dd>
                    </div>
                </dl>
            </div>

            <div class="relative">
                <div class="rounded-[28px] bg-gradient-to-br from-[#0B1E45] via-[#123A6B] to-[#0EA5E9] p-3 shadow-[0_40px_80px_-40px_rgba(11,30,69,0.75)]">
                    <div class="rounded-[20px] bg-[#081A34]/70 p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="h-2.5 w-2.5 rounded-full bg-rose-400/80"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-300/80"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-400/80"></span>
                            </div>
                            <span class="rounded-full bg-white/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-sky-200">Panel SigedTec</span>
                        </div>
                        <x-ui.tech-scene class="w-full" />
                    </div>
                </div>

                <div class="absolute -bottom-5 -left-4 hidden items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-card sm:flex">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <x-ui.icon name="box" class="h-4 w-4" />
                    </span>
                    <div class="leading-tight">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-400">Inventario</p>
                        <p class="text-[13px] font-semibold text-slate-800">Actualizado en tiempo real</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Características ──────────────────────────────────────────────────── --}}
    <section id="caracteristicas" class="border-y border-slate-200 bg-white/70 py-16 lg:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-blue-600">Características</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Todo el control en un solo sistema</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                    Cuatro pilares para que el soporte técnico deje de depender de hojas de cálculo
                    y pase a un flujo verificable de extremo a extremo.
                </p>
            </div>

            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $caracteristicas = [
                        ['icono' => 'box', 'titulo' => 'Inventario', 'texto' => 'Registro de bienes con código patrimonial, especificaciones técnicas y estado actualizado.', 'color' => 'blue'],
                        ['icono' => 'clipboard', 'titulo' => 'Solicitudes', 'texto' => 'El personal solicita equipos en línea y sigue el estado de cada petición.', 'color' => 'sky'],
                        ['icono' => 'swap', 'titulo' => 'Asignaciones', 'texto' => 'Aprobación, rechazo y despacho con bitácora de cada movimiento.', 'color' => 'emerald'],
                        ['icono' => 'chart', 'titulo' => 'Reportes', 'texto' => 'Indicadores de inventario, solicitudes y movimientos listos para consulta.', 'color' => 'amber'],
                    ];
                    $tonos = [
                        'blue' => 'bg-blue-50 text-blue-600 ring-blue-100',
                        'sky' => 'bg-sky-50 text-sky-600 ring-sky-100',
                        'emerald' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                        'amber' => 'bg-amber-50 text-amber-600 ring-amber-100',
                    ];
                @endphp

                @foreach ($caracteristicas as $item)
                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-card transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-panel">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl ring-1 ring-inset {{ $tonos[$item['color']] }}">
                            <x-ui.icon :name="$item['icono']" class="h-5 w-5" />
                        </span>
                        <h3 class="mt-5 text-[15px] font-semibold text-slate-900">{{ $item['titulo'] }}</h3>
                        <p class="mt-2 text-[13px] leading-relaxed text-slate-600">{{ $item['texto'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Cómo funciona ────────────────────────────────────────────────────── --}}
    <section id="como-funciona" class="py-16 lg:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-blue-600">Cómo funciona</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">De la solicitud al despacho, sin papeleo</h2>
                    <p class="mt-3 max-w-lg text-sm leading-relaxed text-slate-600">
                        El flujo está definido en el sistema: cada paso queda registrado con su responsable
                        y su fecha, de modo que cualquier entrega puede auditarse después.
                    </p>

                    <div class="mt-8 space-y-5">
                        @foreach ([
                            ['1', 'Registra la solicitud', 'El solicitante indica la categoría y la cantidad de equipos que necesita.'],
                            ['2', 'Soporte técnico evalúa', 'El administrador aprueba o rechaza la petición con su justificación.'],
                            ['3', 'Despacho y constancia', 'La asignación queda en la bitácora junto al equipo entregado.'],
                        ] as [$numero, $titulo, $texto])
                            <div class="flex gap-4">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-[0_10px_20px_-12px_rgba(37,99,235,0.9)]">{{ $numero }}</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $titulo }}</p>
                                    <p class="mt-1 text-[13px] leading-relaxed text-slate-600">{{ $texto }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-3xl bg-[#081A34] p-8 text-white shadow-[0_40px_80px_-46px_rgba(8,26,52,0.9)]">
                    <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-sky-500/20 blur-2xl"></div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-sky-300 ring-1 ring-inset ring-white/15">
                        <x-ui.icon name="layers" class="h-5 w-5" />
                    </span>
                    <h3 class="mt-5 text-xl font-bold">Un solo flujo institucional</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-300">
                        Inventario, solicitudes, aprobaciones y reportes comparten la misma base de datos,
                        así los indicadores del panel siempre reflejan la realidad del soporte técnico.
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-slate-200">
                        @foreach ([
                            'Acceso diferenciado por rol',
                            'Historial de estados por equipo',
                            'Reportes de inventario y movimientos',
                            'Interfaz en español, lista para escritorio y móvil',
                        ] as $beneficio)
                            <li class="flex items-start gap-2.5">
                                <x-ui.icon name="shield" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-400" />
                                <span>{{ $beneficio }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('login') }}" class="btn mt-7 w-full bg-white text-[#081A34] hover:bg-slate-100">
                        Entrar al sistema
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Llamado final ────────────────────────────────────────────────────── --}}
    <section class="px-4 pb-16 sm:px-6">
        <div class="mx-auto flex max-w-6xl flex-col items-center gap-5 rounded-3xl bg-gradient-to-r from-blue-700 via-blue-600 to-sky-500 px-6 py-10 text-center text-white shadow-[0_30px_60px_-40px_rgba(37,99,235,0.8)] sm:flex-row sm:justify-between sm:text-left">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">¿Listo para ordenar tu inventario?</h2>
                <p class="mt-1.5 text-sm text-blue-50">Inicia sesión con tu cuenta institucional o crea una nueva.</p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('login') }}" class="btn bg-white text-blue-700 hover:bg-blue-50">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn border border-white/50 text-white hover:bg-white/10">Crear una cuenta</a>
            </div>
        </div>
    </section>
</x-layouts.site>
