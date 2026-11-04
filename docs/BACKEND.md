# Backend - SIGEDTEC

Este documento tiene como objetivo llevar el control del backend: rutas, controladores, modelos, reglas y lo que falta por poner en funcionamiento.

## Estado actual

- Laravel 12.70.2
- PHP 8.4.22
- PostgreSQL 5433 (sigedtec)
- SESSION_DRIVER=database
- Todas las migraciones están corridas
- Catálogo de 14 tipos de aparato, cada uno con sus especificaciones técnicas

## Roles

- `admin` - Soporte Técnico
- `solicitante` - Jefe de Oficina

## Módulos

| Módulo | Estado | Comentario |
| :-- | :--- | :--- |
| Autenticación (Breeze) | Funcional | Login, registro, logout. El registro fuerza el rol solicitante y pide oficina activa. |
| Oficinas | Funcional | CRUD completo para admin. Crear, editar, activar/desactivar. |
| Categorías | Funcional | CRUD completo para admin. Cada categoría define qué especificaciones técnicas se piden al registrar un equipo. |
| Inventario (Equipos) | Funcional | CRUD, filtros, baja lógica, restauración, cambio manual de estado con bitácora. El formulario muestra solo las especificaciones de la categoría. |
| Solicitudes | Funcional | Crear, listar propias, ver detalle, cancelar pendiente. |
| Aprobación/Despacho | Funcional | Bandeja, aprobar con selección de equipos, bloqueo, transacción, rechazar con observación. |
| Dashboard | Funcional | Panel por rol con contadores reales, recientes y accesos rápidos. |
| Reportes | Funcional | Inventario, solicitudes, asignaciones, movimientos con filtros. |
| Usuarios | Funcional | Listado y cambio de rol por parte del admin. |

## Especificaciones por categoría

- Columna `categories.spec_fields` (json) con los campos aplicables: `cpu`, `ram_gb`, `storage_gb`, `storage_type`.
- `Category::CAMPOS_TECNICOS` define las etiquetas y `camposTecnicos()` devuelve los de cada categoría; sin configuración se asume equipo de cómputo.
- `EquipoController` anula las especificaciones que no corresponden a la categoría al guardar y al editar, para que no queden datos de otro tipo de aparato.
- Las categorías se crean y editan con casillas para elegir esos campos; el catálogo inicial lo carga `CategorySeeder`.

## Rendimiento

- Panel y bandeja calculan sus contadores con una consulta agregada por tabla (`GROUP BY status`), no una por estado.
- Índices: `status_history(created_at)`, `request_items(created_at)`, `equipment(status, category_id)`, `equipment_requests(status, created_at)` y `equipment_requests(user_id, status)`.
- Los listados piden columnas explícitas y las relaciones cargan solo los campos que usan las vistas.
- La bandeja de despacho conserva el filtro de estado al paginar (`withQueryString`).
- El catálogo de categorías se ordena por cantidad de equipos y se limita a las seis que muestra el panel.

## Rutas activas ahora mismo

Revisando con `route:list` hay rutas de auth, dashboard, profile, oficinas (index), etc.

## Lo que sigue

Todo lo esencial está funcionando. Se puede seguir afinando detalles si se necesitan.

## Notas

- El rol nunca debe venir del formulario.
- Todo lo que sea asignación debe ir por transacción.
- La tabla `sessions` debe existir (ya está).
- Usar `lockForUpdate` cuando se apruebe una solicitud.
- En otra instalación, después de `migrate` conviene ejecutar `php artisan db:seed --class=CategorySeeder` para tener el catálogo completo de aparatos.
