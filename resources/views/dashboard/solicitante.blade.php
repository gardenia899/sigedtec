<x-app-layout>
    <x-slot name="header">Panel de mi oficina</x-slot>

    @php
        $estados = [
            'pendiente' => ['warning', 'Pendiente'],
            'aprobada' => ['success', 'Aprobada'],
            'rechazada' => ['danger', 'Rechazada'],
            'cancelada' => ['neutral', 'Cancelada'],
        ];

        $totalSolicitudes = (int) $porEstado->sum();
        $baseSolicitudes = max(1, $totalSolicitudes);

        $cPendientes = (int) ($porEstado['pendiente'] ?? 0);
        $cAprobadas = (int) ($porEstado['aprobada'] ?? 0);
        $cRechazadas = (int) ($porEstado['rechazada'] ?? 0);
        $cCanceladas = (int) ($porEstado['cancelada'] ?? 0);

        $pPendientes = (int) round($cPendientes * 100 / $baseSolicitudes);
        $corteA = min(100, $pPendientes);
        $pAprobadas = (int) round($cAprobadas * 100 / $baseSolicitudes);
        $corteB = min(100, $corteA + $pAprobadas);
        $pRechazadas = (int) round($cRechazadas * 100 / $baseSolicitudes);
        $corteC = min(100, $corteB + $pRechazadas);

        $tarjetas = [
            ['titulo' => 'Solicitudes pendientes', 'valor' => $pendientes, 'pista' => 'en espera de revisión', 'icono' => 'clock', 'tile' => 'from-amber-400 to-orange-500', 'ruta' => route('solicitudes.index')],
            ['titulo' => 'Mis solicitudes', 'valor' => $totalSolicitudes, 'pista' => 'registradas por mi oficina', 'icono' => 'clipboard', 'tile' => 'from-blue-500 to-blue-600', 'ruta' => route('solicitudes.index')],
            ['titulo' => 'Equipos de mi oficina', 'valor' => $equiposOficina, 'pista' => 'asignados a la oficina', 'icono' => 'monitor', 'tile' => 'from-sky-400 to-sky-600', 'ruta' => route('solicitudes.index')],
        ];

        $acciones = [
            ['etiqueta' => 'Nueva Solicitud', 'detalle' => 'Solicitar equipos tecnológicos', 'icono' => 'plus', 'ruta' => route('solicitudes.create'), 'clase' => 'from-blue-500 to-blue-600'],
            ['etiqueta' => 'Mis Solicitudes', 'detalle' => 'Consultar el estado', 'icono' => 'clipboard', 'ruta' => route('solicitudes.index'), 'clase' => 'from-sky-500 to-cyan-600'],
            ['etiqueta' => 'Mi Perfil', 'detalle' => 'Datos de mi cuenta', 'icono' => 'user', 'ruta' => route('profile.edit'), 'clase' => 'from-slate-600 to-slate-800'],
        ];
    @endphp

    <x-ui.hero
        :title="'¡Bienvenido, '.auth()->user()->name.'!'"
        :subtitle="'Solicita equipos tecnológicos para '.($usuario->office->name ?? 'tu oficina').' y sigue el estado de cada solicitud en tiempo real.'" />

    {{-- Indicadores --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($tarjetas as $tarjeta)
            <a href="{{ $tarjeta['ruta'] }}"
               class="group flex items-center gap-3.5 rounded-xl border border-slate-200 bg-white p-4 shadow-card transition hover:-translate-y-0.5 hover:border-primary-200 hover:shadow-md">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br {{ $tarjeta['tile'] }} text-white shadow-sm">
                    <x-ui.icon :name="$tarjeta['icono']" class="h-7 w-7" />
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-[13px] font-medium text-slate-600">{{ $tarjeta['titulo'] }}</span>
                    <span class="mt-0.5 block text-2xl font-bold leading-tight text-slate-900">{{ $tarjeta['valor'] }}</span>
                    <span class="mt-0.5 block truncate text-[11px] text-slate-500">{{ $tarjeta['pista'] }}</span>
                </span>
                <x-ui.icon name="chevron-right" class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:text-primary-500" />
            </a>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">

            {{-- Mis solicitudes --}}
            <section class="panel">
                <div class="panel-head">
                    <h2 class="panel-title">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-600 text-white">
                            <x-ui.icon name="clipboard" class="h-4 w-4" />
                        </span>
                        Mis Solicitudes Recientes
                    </h2>
                    <a href="{{ route('solicitudes.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800 hover:underline">
                        Ver todas
                    </a>
                </div>

                @if ($solicitudes->isEmpty())
                    <div class="px-4 py-10 text-center">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <x-ui.icon name="clipboard" class="h-6 w-6" />
                        </span>
                        <p class="mt-3 text-sm font-medium text-slate-700">Todavía no ha registrado solicitudes</p>
                        <p class="mt-1 text-xs text-slate-500">Use el botón de nueva solicitud para comenzar.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[560px] text-sm">
                            <thead>
                            <tr class="bg-slate-50 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                <th class="px-4 py-2.5">N°</th>
                                <th class="px-4 py-2.5">Categoría</th>
                                <th class="px-4 py-2.5 text-center">Cantidad</th>
                                <th class="px-4 py-2.5">Fecha</th>
                                <th class="px-4 py-2.5">Estado</th>
                                <th class="px-2 py-2.5"></th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                            @foreach ($solicitudes as $solicitud)
                                @php [$tipo, $etiqueta] = $estados[$solicitud->status] ?? ['neutral', ucfirst($solicitud->status)]; @endphp
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="px-4 py-3 font-mono text-[12px] font-medium text-slate-600">
                                        SOL-{{ str_pad((string) $solicitud->id, 3, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ ucfirst($solicitud->category->name ?? '—') }}</td>
                                    <td class="px-4 py-3 text-center font-semibold text-slate-700">{{ $solicitud->quantity }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-[12px] text-slate-500">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3"><x-ui.badge :type="$tipo" :label="$etiqueta" /></td>
                                    <td class="px-2 py-3 text-right">
                                        <a href="{{ route('solicitudes.show', $solicitud) }}"
                                           class="inline-flex rounded-lg p-1.5 text-slate-400 transition hover:bg-primary-50 hover:text-primary-600"
                                           aria-label="Ver solicitud SOL-{{ $solicitud->id }}">
                                            <x-ui.icon name="chevron-right" class="h-4 w-4" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            {{-- Acciones rápidas --}}
            <section class="panel p-4 sm:p-5">
                <h2 class="panel-title mb-4">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                        <x-ui.icon name="bolt" class="h-4 w-4" />
                    </span>
                    Acciones Rápidas
                </h2>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach ($acciones as $accion)
                        <a href="{{ $accion['ruta'] }}"
                           class="rounded-xl bg-gradient-to-br {{ $accion['clase'] }} p-4 text-center text-white shadow-sm transition hover:brightness-110">
                            <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full border border-white/40 bg-white/10">
                                <x-ui.icon :name="$accion['icono']" class="h-5 w-5" />
                            </span>
                            <span class="mt-3 block text-[13px] font-semibold leading-tight">{{ $accion['etiqueta'] }}</span>
                            <span class="mt-1 block text-[10px] leading-tight text-white/80">{{ $accion['detalle'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- Columna lateral --}}
        <div class="space-y-6">
            <section class="panel p-4 sm:p-5">
                <h2 class="panel-title">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-600 text-white">
                        <x-ui.icon name="chart" class="h-4 w-4" />
                    </span>
                    Estado de mis solicitudes
                </h2>

                @if ($totalSolicitudes === 0)
                    <p class="mt-4 text-sm text-slate-500">Sin solicitudes registradas todavía.</p>
                @else
                    <div class="mt-5 flex items-center gap-5">
                        <div class="relative h-[132px] w-[132px] shrink-0 rounded-full"
                             style="background: conic-gradient(#F59E0B 0% {{ $corteA }}%, #22C55E {{ $corteA }}% {{ $corteB }}%, #F43F5E {{ $corteB }}% {{ $corteC }}%, #94A3B8 {{ $corteC }}% 100%);"
                             role="img"
                             aria-label="Solicitudes: {{ $cPendientes }} pendientes, {{ $cAprobadas }} aprobadas, {{ $cRechazadas }} rechazadas, {{ $cCanceladas }} canceladas">
                            <div class="absolute inset-[16px] flex flex-col items-center justify-center rounded-full bg-white">
                                <span class="text-xl font-bold leading-none text-slate-900">{{ $totalSolicitudes }}</span>
                                <span class="mt-1 text-[10px] leading-none text-slate-500">Solicitudes</span>
                            </div>
                        </div>

                        <ul class="min-w-0 flex-1 space-y-2.5 text-[12px]">
                            <li class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500"></span>
                                <span class="min-w-0 flex-1 truncate text-slate-600">Pendientes</span>
                                <span class="font-semibold text-slate-800">{{ $cPendientes }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-500"></span>
                                <span class="min-w-0 flex-1 truncate text-slate-600">Aprobadas</span>
                                <span class="font-semibold text-slate-800">{{ $cAprobadas }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-rose-500"></span>
                                <span class="min-w-0 flex-1 truncate text-slate-600">Rechazadas</span>
                                <span class="font-semibold text-slate-800">{{ $cRechazadas }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-slate-400"></span>
                                <span class="min-w-0 flex-1 truncate text-slate-600">Canceladas</span>
                                <span class="font-semibold text-slate-800">{{ $cCanceladas }}</span>
                            </li>
                        </ul>
                    </div>
                @endif
            </section>

            <section class="panel p-4 sm:p-5">
                <h2 class="panel-title">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-600 text-white">
                        <x-ui.icon name="building" class="h-4 w-4" />
                    </span>
                    Mi oficina
                </h2>

                <dl class="mt-4 space-y-3 text-[12.5px]">
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-slate-400">Oficina</dt>
                        <dd class="mt-0.5 font-semibold text-slate-800">{{ $usuario->office->name ?? 'Sin oficina asignada' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-slate-400">Código</dt>
                        <dd class="mt-0.5 font-mono text-slate-700">{{ $usuario->office->code ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-slate-400">Equipos asignados</dt>
                        <dd class="mt-0.5 font-semibold text-slate-800">{{ $equiposOficina }}</dd>
                    </div>
                </dl>

                <a href="{{ route('solicitudes.create') }}"
                   class="mt-5 flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-primary-600 text-sm font-semibold text-white transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    <x-ui.icon name="plus" class="h-4 w-4" /> Nueva solicitud
                </a>
            </section>
        </div>
    </div>
</x-app-layout>
