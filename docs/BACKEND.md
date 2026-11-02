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
| Solicitudes | Sin iniciar | Falta crear, ver propias, cancelar. |
| Aprobación/Despacho | Sin iniciar | Falta aprobar/rechazar con selección de equipos, transacción y bloqueo. |
| Dashboard | Parcial | Existe controlador con lógica por rol, pero hay que revisar vistas y datos. |
| Reportes | Sin iniciar | Falta inventario, solicitudes por estado, asignaciones por oficina, movimientos. |

## Rutas activas ahora mismo

Revisando con `route:list` hay rutas de auth, dashboard, profile, oficinas (index), etc.

## Lo que sigue

1. Terminar el módulo de Oficinas (crear/editar/desactivar) con validaciones correctas.
2. Terminar Categorías.
3. Inventario con estados y bitácora.
4. Solicitudes para solicitante.
5. Aprobación/despacho para admin con todo lo transaccional.
6. Ajustar dashboard para mostrar datos reales.
7. Reportes básicos.

## Notas

- El rol nunca debe venir del formulario.
- Todo lo que sea asignación debe ir por transacción.
- La tabla `sessions` debe existir (ya está).
- Usar `lockForUpdate` cuando se apruebe una solicitud.
