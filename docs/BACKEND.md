# Backend - SIGEDTEC

Este documento tiene como objetivo llevar el control del backend: rutas, controladores, modelos, reglas y lo que falta por poner en funcionamiento.

## Estado actual

- Laravel 12.70.2
- PHP 8.4.22
- PostgreSQL 5433 (sigedtec)
- SESSION_DRIVER=database
- Todas las migraciones están corridas

## Roles

- `admin` - Soporte Técnico
- `solicitante` - Jefe de Oficina

## Módulos

| Módulo | Estado | Comentario |
| :-- | :--- | :--- |
| Autenticación (Breeze) | Funcional | Login, registro, logout. El registro fuerza el rol solicitante y pide oficina activa. |
| Oficinas | Funcional | CRUD completo para admin. Crear, editar, activar/desactivar. |
| Categorías | Funcional | CRUD completo para admin. Crear, editar, activar/desactivar. |
| Inventario (Equipos) | Funcional | CRUD, filtros, baja lógica, restauración, cambio manual de estado con bitácora. |
| Solicitudes | Funcional | Crear, listar propias, ver detalle, cancelar pendiente. |
| Aprobación/Despacho | Funcional | Bandeja, aprobar con selección de equipos, bloqueo, transacción, rechazar con observación. |
| Dashboard | Funcional | Panel por rol con contadores reales, recientes y accesos rápidos. |
| Reportes | Funcional | Inventario, solicitudes, asignaciones, movimientos con filtros. |

## Rutas activas ahora mismo

Revisando con `route:list` hay rutas de auth, dashboard, profile, oficinas (index), etc.

## Lo que sigue

Todo lo esencial está funcionando. Se puede seguir afinando detalles si se necesitan.

## Notas

- El rol nunca debe venir del formulario.
- Todo lo que sea asignación debe ir por transacción.
- La tabla `sessions` debe existir (ya está).
- Usar `lockForUpdate` cuando se apruebe una solicitud.
