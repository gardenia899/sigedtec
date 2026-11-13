<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #172033; }
        h1 { margin: 0 0 4px; font-size: 18px; }
        p { margin: 0 0 14px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px; border: 1px solid #cbd5e1; text-align: left; vertical-align: top; }
        th { background: #eaf1f8; font-weight: bold; }
        tr:nth-child(even) { background: #f8fafc; }
    </style>
</head>
<body>
    <h1>
        @switch($tipo)
            @case('inventario') Reporte de inventario @break
            @case('solicitudes') Reporte de solicitudes @break
            @case('asignaciones') Reporte de asignaciones @break
            @case('movimientos') Reporte de movimientos @break
        @endswitch
    </h1>
    <p>SIGEDTEC · Generado el {{ $generado->format('d/m/Y H:i') }} · Registros: {{ $registros->count() }}</p>

    <table>
        <thead>
        <tr>
            @if ($tipo === 'inventario')
                <th>Código</th><th>Categoría</th><th>Marca / Modelo</th><th>Serie</th><th>Estado</th>
            @elseif ($tipo === 'solicitudes')
                <th>N°</th><th>Oficina</th><th>Solicitante</th><th>Categoría</th><th>Cantidad</th><th>Fecha</th><th>Estado</th>
            @elseif ($tipo === 'asignaciones')
                <th>Fecha</th><th>Solicitud</th><th>Oficina</th><th>Solicitante</th><th>Categoría</th><th>Equipo</th><th>Asignado por</th>
            @else
                <th>Fecha</th><th>Equipo</th><th>De</th><th>A</th><th>Motivo</th><th>Usuario</th><th>Nota</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse ($registros as $registro)
            <tr>
                @if ($tipo === 'inventario')
                    <td>{{ $registro->patrimonial_code }}</td>
                    <td>{{ ucfirst($registro->category->name ?? '—') }}</td>
                    <td>{{ $registro->brand }} / {{ $registro->model }}</td>
                    <td>{{ $registro->serial_number ?? '—' }}</td>
                    <td>{{ ucfirst($registro->status) }}</td>
                @elseif ($tipo === 'solicitudes')
                    <td>#{{ str_pad((string) $registro->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $registro->office->name ?? '—' }}</td>
                    <td>{{ $registro->user->name ?? '—' }}</td>
                    <td>{{ ucfirst($registro->category->name ?? '—') }}</td>
                    <td>{{ $registro->quantity }}</td>
                    <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($registro->status) }}</td>
                @elseif ($tipo === 'asignaciones')
                    <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                    <td>#{{ str_pad((string) $registro->request_id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $registro->request->office->name ?? '—' }}</td>
                    <td>{{ $registro->request->user->name ?? '—' }}</td>
                    <td>{{ ucfirst($registro->request->category->name ?? '—') }}</td>
                    <td>{{ $registro->equipment->patrimonial_code ?? '—' }} · {{ $registro->equipment->brand ?? '' }} {{ $registro->equipment->model ?? '' }}</td>
                    <td>{{ $registro->assignedBy->name ?? '—' }}</td>
                @else
                    <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $registro->equipment->patrimonial_code ?? '—' }}</td>
                    <td>{{ $registro->from_status ? ucfirst($registro->from_status) : '—' }}</td>
                    <td>{{ ucfirst($registro->to_status) }}</td>
                    <td>{{ $registro->reason }}</td>
                    <td>{{ $registro->user->name ?? '—' }}</td>
                    <td>{{ $registro->notes ?? '—' }}</td>
                @endif
            </tr>
        @empty
            <tr><td colspan="7">No hay registros para los filtros seleccionados.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
