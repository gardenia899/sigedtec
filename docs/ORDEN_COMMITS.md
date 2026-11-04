# Orden de commits

Este archivo lleva el seguimiento de los commits que se van subiendo al repositorio https://github.com/gardenia899/sigedtec.git

Todo se hace con el usuario configurado: Yocelianna Rodriguez

## Historial

| # | Fecha | Commit | Comentario |
| :-- | :--- | :--- | :--- |
| 1 | 2026-10-27 | `docs: agregar creditos de desarrollo a Yocelianna Rodriguez` | Se agregaron créditos a Yocelianna Rodriguez en README.md y se agregó .env.example |
| 2 | 2026-10-27 | `config: actualizar APP_URL a http://127.0.0.1:8001` | Se ajusta APP_URL para poder trabajar en puerto 8001 y evitar el error 419 al registrarse |
| 3 | 2026-10-27 | `chore: sincronizar estado del proyecto` | Sincronización del proyecto tal como quedó funcionando |
| 4 | 2026-11-01 | `docs: agregar seguimiento de commits y guias backend/frontend` | Se crean docs/ORDEN_COMMITS.md, docs/BACKEND.md, docs/FRONTEND.md para llevar control entre avances |
| 5 | 2026-11-01 | `docs: actualizar orden de commits` | Actualización del historial |
| 6 | 2026-11-01 | `feat: completar CRUD de oficinas para admin` | Se agregan crear/editar/desactivar oficinas con validaciones |
| 7 | 2026-11-01 | `feat: agregar CRUD de categorias para admin` | Se agregan crear/editar/desactivar categorías con validaciones |
| 8 | 2026-11-01 | `feat: agregar inventario de equipos con CRUD, filtros, baja y cambio de estado` | Se agrega módulo de equipos completo para admin |
| 9 | 2026-11-01 | `docs: actualizar orden y estado con inventario` | Se actualiza seguimiento con inventario |
| 10 | 2026-11-01 | `feat: agregar modulo de solicitudes para solicitante` | Solicitar, listar, ver detalle, cancelar pendiente |
| 11 | 2026-11-01 | `docs: actualizar seguimiento con solicitudes` | Se actualiza estado de backend/frontend |
| 12 | 2026-11-01 | `feat: agregar aprobacion y despacho de solicitudes con transaccion` | Aprobar/rechazar con bloqueo, asignación exacta y bitácora |
| 13 | 2026-11-01 | `docs: actualizar seguimiento con aprobacion y despacho` | Se actualiza estado de backend/frontend |
| 14 | 2026-11-01 | `feat: agregar reportes basicos para admin` | Inventario, solicitudes, asignaciones y movimientos |
| 15 | 2026-11-01 | `docs: actualizar seguimiento con reportes` | Se actualiza estado de backend/frontend |
| 16 | 2026-11-01 | `feat: actualizar menu segun rol para acceder a todos los modulos` | Se agregan accesos directos al menú |
| 17 | 2026-11-02 | `feat: mejorar dashboard con acciones rapidas` | Se agregan accesos directos en dashboard |
| 18 | 2026-11-02 | `fix: permitir actualizar status de equipos desde controlador` | Se corrige mass assignment para cambio de estado |
| 19 | 2026-11-03 | `estilo: ajustar colores de estados en solicitudes` | Se mejoran colores en bandeja de solicitudes |
| 20 | 2026-11-03 | `menu: agregar accesos a todos los reportes` | Se agregan enlaces a reportes en menú |
| 21 | 2026-11-03 | `docs: actualizar estado backend a funcional` | Se actualiza estado del backend |
| 22 | 2026-11-03 | `feat: permitir ver detalle de solicitudes al admin` | El admin ya puede ver detalle de todas las solicitudes |
| 23 | 2026-11-03 | `mejora: enlazar solicitudes recientes en dashboard` | Se enlazan solicitudes al detalle desde dashboard |
| 24 | 2026-11-03 | `fix: mejorar validacion de equipos con mensajes claros` | Se ajustan validaciones únicas para equipos |
| 25 | 2026-11-03 | `fix: mensajes de validacion en categorias` | Se agregan mensajes claros para categorías |
| 26 | 2026-11-03 | `feat: agregar modulo de usuarios para cambiar roles` | Se crea módulo de usuarios para que admin cambie roles |
| 27 | 2026-11-05 | `feat: rediseñar la interfaz y adaptar las especificaciones a cada categoría` | Portada pública, design system en todos los módulos, especificaciones técnicas según la categoría del equipo y catálogo de 14 tipos de aparato |
| 28 | 2026-11-05 | `perf: indexar las consultas mas usadas y limitar columnas en los listados` | Índices para panel, bandeja y reportes; columnas explícitas en los listados y paginación que conserva el filtro |
| 29 | 2026-11-05 | `docs: actualizar estado backend y frontend` | Se documentan las especificaciones por categoría, la portada pública y las optimizaciones |
| 30 | 2026-11-05 | `feat: agregar pantalla de carga y corregir detalles de interfaz` | Pantalla de carga en los tres layouts, icono de retroceso, cambio de rol con el design system y .gitignore para archivos de instrucciones de IA |
| 31 | 2026-11-05 | `docs: actualizar orden de commits y detalles del sistema` | Se documentan la pantalla de carga, la portada, las especificaciones por categoría y los índices |


## Pendientes por hacer

Se irán agregando aquí a medida que se avance. Cada cambio funcional irá con su propio commit, corto y directo.
