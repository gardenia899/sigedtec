# Backend de SIGEDTEC

Este documento describe el comportamiento actual del servidor. La especificación de diseño y sus decisiones históricas están en [SDD.md](../SDD.md); donde difieran, prevalece la implementación documentada aquí y en el README.

## Tecnologías y datos

- PHP 8.2 o superior y Laravel 12.
- PostgreSQL como base de datos de la instalación de desarrollo.
- Eloquent ORM, migraciones y Form Requests para persistencia y validación.
- PHPUnit, ejecutado con `php artisan test`.
- Dompdf para generar los reportes PDF.

La aplicación tiene roles `admin` y `solicitante`. El middleware de rol protege las rutas administrativas y las operaciones comprueban además la pertenencia de las solicitudes. El rol no se acepta como dato confiable del formulario de registro.

## Módulos

| Módulo | Funciones |
| --- | --- |
| Autenticación y perfiles | Iniciar y cerrar sesión, registro de solicitantes, recuperación de cuenta y edición del perfil. |
| Oficinas | Consulta, alta, edición y activación/desactivación. Los códigos se generan en el servidor. |
| Categorías | Consulta, alta, edición y activación/desactivación; define los campos técnicos de los equipos. |
| Inventario | Alta y edición, filtros, baja lógica y restauración. Los códigos patrimoniales se generan al guardar. |
| Solicitudes | Crear solicitudes, consultar las propias, ver su detalle y cancelar las pendientes. |
| Aprobación y despacho | Revisar solicitudes, seleccionar los equipos disponibles requeridos o rechazar indicando una observación. |
| Devoluciones | El solicitante pide devolver un equipo asignado; Soporte Técnico confirma su condición, cierra la asignación y lo libera como disponible o lo envía a mantenimiento. |
| Dashboard y notificaciones | Mostrar indicadores por perfil y accesos a solicitudes recientes. |
| Reportes | Consultar inventario, solicitudes, asignaciones y movimientos con filtros y exportación PDF. |
| Usuarios | Consultar cuentas y cambiar roles con permisos de administrador. |

## Reglas relevantes

- Los códigos patrimoniales y de oficina se generan en el servidor, no se aceptan como valores editables del formulario.
- Los campos técnicos guardados para un equipo dependen de su categoría.
- Un equipo asignado no se cambia a otro estado desde la acción de inventario; la asignación ocurre al procesar una solicitud.
- Antes de confirmar un despacho, se verifica nuevamente que la solicitud siga pendiente y que los equipos continúen disponibles. Los cambios se guardan en una transacción y quedan relacionados con la solicitud y el historial de estados.
- Una solicitud de devolución no cambia por sí sola el estado del equipo. Un administrador confirma la condición del equipo; el cierre de la asignación, actualización del estado y bitácora se guardan juntos en una transacción.
- Una baja es lógica; los registros históricos no se eliminan de la base.
- Los reportes PDF aplican los filtros seleccionados en el reporte.

## Datos y migraciones

Las migraciones de `database/migrations` definen usuarios, oficinas, categorías, equipos, solicitudes, elementos asignados, bitácora y secuencias de códigos, además de tablas de soporte de Laravel. Las restricciones y relaciones en la base complementan la validación de Laravel.

`DatabaseSeeder` llama a los seeders de oficinas, categorías, usuarios, equipos y solicitudes. Para cargar solamente el catálogo de categorías en una instalación existente:

```powershell
php artisan db:seed --class=CategorySeeder
```

Revisa siempre `.env` antes de migrar o ejecutar seeders. Evita `migrate:fresh` si la base contiene información que deba conservarse.

## Comprobaciones

```powershell
php artisan test
vendor/bin/pint --test
```

La suite debe ejecutarse contra la base de pruebas configurada en `phpunit.xml`, nunca contra la base activa con datos institucionales.
