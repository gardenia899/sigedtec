<x-app-layout>
    <x-slot name="header">Panel de Soporte Técnico</x-slot>

    @php
        $totalEquipos = $disponibles + $asignados + $mantenimiento;
        $base = max(1, $totalEquipos);

        $pDisponibles = (int) round($disponibles * 100 / $base);
        $pAsignados = (int) round($asignados * 100 / $base);
        $corteA = min(100, $pDisponibles);
        $corteB = min(100, $pDisponibles + $pAsignados);
        $pMantenimiento = max(0, 100 - $corteB);

        $maxCategoria = max(1, (int) $porCategoria->max('equipments_count'));

        $estados = [
            'pendiente' => ['warning', 'Pendiente'],
            'aprobada' => ['success', 'Aprobada'],
            'rechazada' => ['danger', 'Rechazada'],
            'cancelada' => ['neutral', 'Cancelada'],
        ];

        $tarjetas = [
            ['titulo' => 'Equipos disponibles', 'valor' => $disponibles, 'pista' => 'de '.$totalEquipos.' equipos totales', 'icono' => 'monitor', 'tile' => 'from-emerald-400 to-emerald-600', 'ruta' => route('equipos.index', ['estado' => 'disponible'])],
            ['titulo' => 'Equipos asignados', 'valor' => $asignados, 'pista' => 'de '.$totalEquipos.' equipos totales', 'icono' => 'swap', 'tile' => 'from-sky-400 to-sky-600', 'ruta' => route('equipos.index', ['estado' => 'asignado'])],
            ['titulo' => 'En mantenimiento', 'valor' => $mantenimiento, 'pista' => 'de '.$totalEquipos.' equipos totales', 'icono' => 'wrench', 'tile' => 'from-amber-400 to-orange-500', 'ruta' => route('equipos.index', ['estado' => 'mantenimiento'])],
            ['titulo' => 'Solicitudes pendientes', 'valor' => $pendientes, 'pista' => 'de '.$totalSolicitudes.' solicitudes totales', 'icono' => 'clipboard', 'tile' => 'from-rose-400 to-rose-600', 'ruta' => route('admin.solicitudes.index', ['estado' => 'pendiente'])],
        ];

        $acciones = [
            ['etiqueta' => 'Nuevo Equipo', 'detalle' => 'Agregar al inventario', 'icono' => 'plus', 'ruta' => route('equipos.create'), 'clase' => 'from-blue-500 to-blue-600'],
            ['etiqueta' => 'Ver Inventario', 'detalle' => 'Consultar equipos', 'icono' => 'box', 'ruta' => route('equipos.index'), 'clase' => 'from-violet-500 to-violet-600'],
            ['etiqueta' => 'Solicitudes', 'detalle' => 'Aprobar o rechazar', 'icono' => 'clipboard', 'ruta' => route('admin.solicitudes.index'), 'clase' => 'from-emerald-500 to-teal-600'],
            ['etiqueta' => 'Reportes', 'detalle' => 'Historial y estadísticas', 'icono' => 'chart', 'ruta' => route('reportes.inventario'), 'clase' => 'from-slate-600 to-slate-800'],
        ];

        $accesos = [
            ['titulo' => 'Historial de asignaciones', 'detalle' => 'Consulta por oficina y fecha', 'icono' => 'clock', 'ruta' => route('reportes.asignaciones')],
            ['titulo' => 'Gestión de solicitudes', 'detalle' => 'Aprobar y despachar equipos', 'icono' => 'users', 'ruta' => route('admin.solicitudes.index')],
            ['titulo' => 'Catálogos institucionales', 'detalle' => 'Oficinas y categorías', 'icono' => 'building', 'ruta' => route('oficinas.index')],
        ];
    @endphp

    <x-ui.hero
        :title="'¡Bienvenido, '.auth()->user()->name.'!'"
        subtitle="Aquí puedes gestionar el inventario de equipos tecnológicos, revisar solicitudes y realizar despachos de manera eficiente." />

    {{-- Indicadores reales de PostgreSQL (RN-22) --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
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

            {{-- Últimas solicitudes --}}
            <section class="panel">
                <div class="panel-head">
                    <h2 class="panel-title">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-600 text-white">
                            <x-ui.icon name="clipboard" class="h-4 w-4" />
                        </span>
                        Últimas Solicitudes
                    </h2>
                    <a href="{{ route('admin.solicitudes.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800 hover:underline">
                        Ver todas
                    </a>
                </div>

                @if ($solicitudesRecientes->isEmpty())
                    <p class="px-4 py-8 text-center text-sm text-slate-500">Aún no hay solicitudes registradas.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[640px] text-sm">
                            <thead>
                            <tr class="bg-slate-50 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                <th class="px-4 py-2.5">N°</th>
                                <th class="px-4 py-2.5">Oficina</th>
                                <th class="px-4 py-2.5">Categoría</th>
                                <th class="px-4 py-2.5 text-center">Cantidad</th>
                                <th class="px-4 py-2.5">Estado</th>
                                <th class="px-4 py-2.5">Fecha</th>
                                <th class="px-2 py-2.5"></th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                            @foreach ($solicitudesRecientes as $solicitud)
                                @php [$tipo, $etiqueta] = $estados[$solicitud->status] ?? ['neutral', ucfirst($solicitud->status)]; @endphp
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="px-4 py-3 font-mono text-[12px] font-medium text-slate-600">
                                        SOL-{{ str_pad((string) $solicitud->id, 3, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $solicitud->office->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ ucfirst($solicitud->category->name ?? '—') }}</td>
                                    <td class="px-4 py-3 text-center font-semibold text-slate-700">{{ $solicitud->quantity }}</td>
                                    <td class="px-4 py-3"><x-ui.badge :type="$tipo" :label="$etiqueta" /></td>
                                    <td class="whitespace-nowrap px-4 py-3 text-[12px] text-slate-500">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
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

                <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                    @foreach ($acciones as $accion)
                        <a href="{{ $accion['ruta'] }}"
                           class="group rounded-xl bg-gradient-to-br {{ $accion['clase'] }} p-4 text-center text-white shadow-sm transition hover:brightness-110">
                            <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full border border-white/40 bg-white/10">
                                <x-ui.icon :name="$accion['icono']" class="h-5 w-5" />
                            </span>
                            <span class="mt-3 block text-[13px] font-semibold leading-tight">{{ $accion['etiqueta'] }}</span>
                            <span class="mt-1 block text-[10px] leading-tight text-white/80">{{ $accion['detalle'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- Movimientos recientes --}}
            <section class="panel">
                <div class="panel-head">
                    <h2 class="panel-title">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-white">
                            <x-ui.icon name="swap" class="h-4 w-4" />
                        </span>
                        Movimientos de Inventario
                    </h2>
                    <a href="{{ route('reportes.movimientos') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800 hover:underline">
                        Ver bitácora
                    </a>
                </div>

                @if ($movimientosRecientes->isEmpty())
                    <p class="px-4 py-8 text-center text-sm text-slate-500">Sin movimientos registrados.</p>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($movimientosRecientes as $movimiento)
                            <li class="flex items-center gap-3 px-4 py-2.5 text-sm">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                    <x-ui.icon name="box" class="h-4 w-4" />
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[13px] font-medium text-slate-800">
                                        {{ $movimiento->equipment->patrimonial_code ?? '—' }}
                                        <span class="font-normal text-slate-500">→ {{ ucfirst($movimiento->to_status) }}</span>
                                    </p>
                                    <p class="truncate text-[11px] text-slate-500">
                                        {{ $movimiento->reason }} · {{ $movimiento->user->name ?? 'Sistema' }}
                                    </p>
                                </div>
                                <time class="shrink-0 text-[11px] text-slate-400" datetime="{{ $movimiento->created_at->toIso8601String() }}">
                                    {{ $movimiento->created_at->format('d/m/Y H:i') }}
                                </time>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        {{-- Columna lateral --}}
        <div class="space-y-6">

            {{-- Estado del inventario --}}
            <section class="panel p-4 sm:p-5">
                <h2 class="panel-title">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-600 text-white">
                        <x-ui.icon name="chart" class="h-4 w-4" />
                    </span>
                    Estado del Inventario
                </h2>

                <div class="mt-5 flex items-center gap-5">
                    <div class="relative h-[132px] w-[132px] shrink-0 rounded-full"
                         style="background: conic-gradient(#22C55E 0% {{ $corteA }}%, #3B82F6 {{ $corteA }}% {{ $corteB }}%, #F59E0B {{ $corteB }}% 100%);"
                         role="img"
                         aria-label="Distribución del inventario: {{ $disponibles }} disponibles, {{ $asignados }} asignados, {{ $mantenimiento }} en mantenimiento">
                        <div class="absolute inset-[16px] flex flex-col items-center justify-center rounded-full bg-white">
                            <span class="text-xl font-bold leading-none text-slate-900">{{ $totalEquipos }}</span>
                            <span class="mt-1 text-[10px] leading-none text-slate-500">Equipos</span>
                        </div>
                    </div>

                    <ul class="min-w-0 flex-1 space-y-2.5 text-[12px]">
                        <li class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-500"></span>
                            <span class="min-w-0 flex-1 truncate text-slate-600">Disponibles</span>
                            <span class="font-semibold text-slate-800">{{ $disponibles }}</span>
                            <span class="w-10 text-right text-[11px] text-slate-400">({{ $pDisponibles }}%)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-blue-500"></span>
                            <span class="min-w-0 flex-1 truncate text-slate-600">Asignados</span>
                            <span class="font-semibold text-slate-800">{{ $asignados }}</span>
                            <span class="w-10 text-right text-[11px] text-slate-400">({{ $pAsignados }}%)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500"></span>
                            <span class="min-w-0 flex-1 truncate text-slate-600">En mantenimiento</span>
                            <span class="font-semibold text-slate-800">{{ $mantenimiento }}</span>
                            <span class="w-10 text-right text-[11px] text-slate-400">({{ $pMantenimiento }}%)</span>
                        </li>
                    </ul>
                </div>
            </section>

            {{-- Categorías --}}
            <section class="panel p-4 sm:p-5">
                <h2 class="panel-title">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-600 text-white">
                        <x-ui.icon name="folders" class="h-4 w-4" />
                    </span>
                    Categorías de Equipos
                </h2>

                @if ($porCategoria->isEmpty())
                    <p class="mt-4 text-sm text-slate-500">Sin categorías registradas.</p>
                @else
                    <div class="mt-5 flex h-[150px] items-end gap-3">
                        @foreach ($porCategoria->take(6) as $categoria)
                            <div class="flex h-full min-w-0 flex-1 flex-col items-center justify-end gap-1.5">
                                <span class="text-[11px] font-semibold text-slate-600">{{ $categoria->equipments_count }}</span>
                                <div class="flex w-full flex-1 items-end">
                                    <div class="w-full rounded-t-md bg-gradient-to-t from-blue-600 to-sky-400"
                                         style="height: {{ max(6, (int) round($categoria->equipments_count * 100 / $maxCategoria)) }}%"
                                         title="{{ $categoria->name }}: {{ $categoria->equipments_count }} equipos"></div>
                                </div>
                                <span class="w-full truncate text-center text-[10px] text-slate-500">{{ ucfirst($categoria->name) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- Acceso rápido --}}
            <section class="panel p-4 sm:p-5">
                <h2 class="panel-title">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </span>
                    Acceso Rápido
                </h2>

                <ul class="mt-4 space-y-2">
                    @foreach ($accesos as $acceso)
                        <li>
                            <a href="{{ $acceso['ruta'] }}"
                               class="group flex items-center gap-3 rounded-xl border border-slate-100 px-3 py-2.5 transition hover:border-primary-200 hover:bg-primary-50/40">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sky-600">
                                    <x-ui.icon :name="$acceso['icono']" class="h-4 w-4" />
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-[12.5px] font-semibold text-slate-800">{{ $acceso['titulo'] }}</span>
                                    <span class="block truncate text-[11px] text-slate-500">{{ $acceso['detalle'] }}</span>
                                </span>
                                <x-ui.icon name="chevron-right" class="h-4 w-4 shrink-0 text-slate-300 group-hover:text-primary-500" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>
</x-app-layout>
