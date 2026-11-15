# Guía técnica y de uso de SIGEDTEC

**Sistema de Gestión de Inventario y Despacho Tecnológico**
**Fecha de revisión:** 15 de noviembre de 2026

## 1. ¿Para qué sirve?

SIGEDTEC centraliza el inventario de bienes informáticos de una institución y el proceso para solicitarlos, aprobar su entrega, gestionar devoluciones y dejar constancia de los equipos asignados. También administra los catálogos de oficinas y categorías, registra cambios en una bitácora y ofrece reportes descargables en PDF.

Su objetivo principal es que el inventario y cada decisión de asignación queden organizados, consultables y vinculados con el usuario responsable. No sustituye políticas institucionales de autorización ni es un sistema contable.

## 2. Tecnologías y lenguajes

| Área | Tecnología utilizada |
| --- | --- |
| Aplicación de servidor | PHP 8.4.22 y Laravel 12.70.2 |
| Base de datos activa | PostgreSQL, conexión local de desarrollo `sigedtec` |
| Acceso a datos | Eloquent ORM, consultas Laravel y migraciones |
| Vistas | Blade, componentes Blade reutilizables y HTML |
| Estilos | Tailwind CSS y CSS propio |
| Interactividad | JavaScript, Alpine.js y Axios |
| Compilación de recursos | Vite 7 |
| Exportación de reportes | Dompdf mediante `barryvdh/laravel-dompdf` 3.1.2 |
| Pruebas | PHPUnit mediante `php artisan test` |
| Idioma de la interfaz | Español |

La aplicación es un monolito web renderizado en el servidor: el navegador solicita páginas HTML; Laravel valida y procesa las operaciones; PostgreSQL conserva los datos. No es una SPA y no depende de una API REST para los flujos principales.

El idioma visible para las personas usuarias es español. La zona horaria configurada es `America/Caracas`. La configuración de idioma interna de Laravel puede ser distinta de los textos de la interfaz.

## 3. Perfiles de usuario

### Administrador (Soporte Técnico)

Puede administrar oficinas y categorías, registrar y editar equipos, revisar solicitudes, escoger los equipos específicos que se despacharán, confirmar las devoluciones recibidas, consultar los reportes y cambiar roles de otros usuarios. No puede cambiar su propio rol desde la gestión de usuarios.

### Solicitante (Jefe de Oficina)

Puede crear solicitudes para la oficina asociada a su cuenta, consultar sus propias solicitudes, abrir su detalle, solicitar la devolución de equipos asignados y cancelar una solicitud mientras siga pendiente. No puede consultar ni procesar solicitudes pertenecientes a otros usuarios.

El servidor comprueba los permisos y la propiedad de los datos; ocultar un botón en la interfaz no es el mecanismo de autorización.

## 4. Navegación, vistas y acciones

### Acceso y cuenta

- **Iniciar sesión:** autentica con correo y contraseña.
- **Mantener sesión activa:** solicita conservar la sesión según el mecanismo de autenticación.
- **Mostrar/Ocultar contraseña:** alterna la visibilidad del campo.
- **Crear una cuenta:** abre el registro de solicitantes, sujeto a las oficinas disponibles.
- **¿Olvidaste tu contraseña?:** inicia el flujo de recuperación habilitado por la aplicación.
- **Mi perfil:** permite actualizar la información de la cuenta.
- **Cerrar sesión:** termina la sesión.

### Inicio del administrador

- **Tarjetas de equipos y solicitudes:** abren el inventario o la bandeja con el filtro correspondiente.
- **Nuevo Equipo:** abre el formulario de alta en el inventario.
- **Ver Inventario:** abre el listado.
- **Solicitudes:** lleva a la bandeja para aprobar o rechazar.
- **Devoluciones:** abre las solicitudes de devolución pendientes de revisión.
- **Reportes:** abre el reporte de inventario.
- **Historial de asignaciones / Gestión de solicitudes / Catálogos institucionales:** atajos a esos módulos.
- **Campana:** abre solicitudes pendientes recientes; cada elemento lleva al procesamiento de la solicitud. **Ver todas las solicitudes** abre la bandeja completa.
- **Búsqueda superior:** busca equipos en el inventario.

### Inicio del solicitante

- **Nueva solicitud:** abre el formulario para escoger una categoría activa, cantidad y justificación.
- **Mis solicitudes:** abre el historial de solicitudes propias.
- **Tarjetas de resumen:** abren la lista de solicitudes.
- **Campana:** presenta solicitudes recientes propias; un elemento abre su detalle y **Ver todas las solicitudes** abre el listado.

### Inventario

- **Activos / Dados de baja:** alterna el listado de equipos vigentes y los eliminados lógicamente.
- **Nuevo equipo:** abre una ventana modal para ingresar categoría, marca, modelo, serie, estado inicial, especificaciones y observaciones. El código patrimonial se propone en pantalla, es de solo lectura y se reserva al guardar.
- **Filtrar / Limpiar:** busca por texto, categoría y estado, o elimina los filtros.
- **Editar:** abre los datos del equipo, donde se puede corregir la información y, si no está asignado, cambiar entre disponible y mantenimiento. Los cambios de estado quedan en la bitácora.
- **Baja:** realiza una baja lógica; no está permitida mientras el equipo esté asignado.
- **Restaurar:** recupera un registro desde “Dados de baja”.
- Los equipos solo pasan a **asignado** al aprobar y despachar una solicitud; no se asignan con el formulario de edición.

### Solicitudes del solicitante

- **Nueva solicitud:** abre el formulario.
- **Enviar solicitud:** registra la petición con la oficina vinculada al usuario.
- **Cancelar:** vuelve al listado sin guardar.
- **Ver:** abre el detalle, estado, observación del administrador y, si fue aprobada, los equipos asignados.
- **Solicitar devolución:** desde el detalle de una solicitud aprobada, pide la devolución de un equipo. El equipo no se libera hasta que Soporte Técnico la confirme.
- **Cancelar solicitud** en el listado solo está disponible mientras el estado sea pendiente.

### Bandeja de solicitudes del administrador

- **Pendientes / Aprobadas / Rechazadas / Canceladas / Todas:** filtra por estado; el contador muestra cuántas hay en cada estado.
- **Ver:** abre el detalle de una solicitud.
- **Aprobar:** abre la selección de equipos disponibles que coinciden con la categoría. Debe seleccionarse la cantidad exacta pedida; la aplicación vuelve a comprobar disponibilidad al guardar.
- **Aprobar y asignar:** registra la decisión, asigna los equipos seleccionados y crea los registros de asignación y bitácora dentro de una transacción.
- **Rechazar:** solicita una observación y registra el motivo, responsable y fecha.
- Una solicitud ya procesada no puede aprobarse o rechazarse por segunda vez.

### Devoluciones de equipos

- **Solicitar devolución:** el solicitante indica opcionalmente una observación desde el detalle de su propia solicitud aprobada.
- **Revisión:** Soporte Técnico abre **Devoluciones**, revisa el equipo recibido y confirma si está operativo o requiere mantenimiento.
- **Confirmar devolución:** si está operativo, el equipo queda disponible; si requiere mantenimiento, pasa a mantenimiento. Ambas decisiones cierran la asignación y se registran en la bitácora.
- Mientras está pendiente, el equipo sigue asignado y no puede despacharse a otra solicitud.

### Catálogos

- **Categorías:** Nueva categoría, Editar, Guardar, Activar y Desactivar. La categoría define qué especificaciones técnicas aparecen al registrar un equipo. No se desactiva si tiene equipos vinculados.
- **Oficinas:** Nueva oficina, Editar, Guardar, Activar y Desactivar. El código se genera en el servidor y no se puede escribir ni modificar. No se desactiva si tiene usuarios asociados.
- **Cancelar / Volver al catálogo:** sale del formulario sin guardar.

### Usuarios

- **Cambiar rol:** abre el formulario del usuario seleccionado.
- **Guardar cambios:** aplica el rol seleccionado, administrador o solicitante.
- La cuenta de la persona administradora actual se muestra como **No editable** para impedir que se quite sus propios permisos desde esta pantalla.

### Reportes

Todos los reportes tienen **Filtrar**, **Limpiar filtros**, paginación y **Exportar PDF**. La descarga utiliza los mismos filtros seleccionados en pantalla e incluye todos los resultados que coinciden, no solo la página visible.

- **Reporte de inventario:** buscar texto, categoría y estado.
- **Solicitudes por estado:** estado y oficina.
- **Historial de asignaciones:** oficina y rango de fechas.
- **Movimientos de inventario:** rango de fechas y detalle de cambios registrados.

## 5. Flujo de trabajo principal

1. Soporte Técnico configura las oficinas y categorías; en cada categoría define qué especificaciones aplican.
2. Se registran equipos desde Inventario. El sistema genera el código patrimonial y el equipo queda disponible o en mantenimiento.
3. La persona solicitante registra una petición seleccionando categoría, cantidad y justificación. La oficina se toma de su cuenta y la petición queda pendiente.
4. El administrador revisa la bandeja y, para aprobar, selecciona equipos disponibles de esa categoría en la cantidad solicitada. También puede rechazar indicando una observación.
5. Si aprueba, el sistema confirma nuevamente que cada equipo siga disponible, marca los equipos como asignados y registra la relación con la solicitud y el administrador en una única transacción.
6. La persona solicitante consulta el resultado y los códigos de los equipos asignados desde el detalle de su solicitud.
7. El solicitante pide la devolución desde el detalle de la solicitud aprobada. El equipo continúa asignado hasta que Soporte Técnico revise su condición y confirme la devolución.
8. Soporte Técnico confirma si el equipo está operativo (vuelve a disponible) o requiere mantenimiento. La asignación se cierra y el cambio queda en la bitácora.
9. El administrador consulta los reportes y la bitácora para seguimiento.

## 6. Datos principales

| Tabla | Propósito |
| --- | --- |
| `users` | Cuentas, credenciales protegidas, rol y oficina asociada |
| `offices` | Catálogo de oficinas y código institucional |
| `categories` | Clasificación y campos técnicos de los equipos |
| `equipment` | Inventario, especificaciones, estado y baja lógica |
| `equipment_requests` | Solicitudes, justificación, estado y procesamiento |
| `request_items` | Equipos específicos asociados a una solicitud |
| `status_history` | Bitácora de creación y cambios de estado |
| `equipment_code_sequences` | Secuencia anual de códigos patrimoniales |
| `office_code_sequences` | Secuencia de códigos automáticos de oficinas |

También hay tablas de soporte de Laravel para migraciones, sesiones, caché y trabajos en cola. Las relaciones entre tablas usan claves foráneas; la base de datos impone restricciones de estado, cantidades positivas, unicidad y evita más de una asignación activa por equipo.

Los equipos se dan de baja mediante borrado lógico para permitir su recuperación. La bitácora de estados es histórica y de solo anexado.

## 7. Respaldo de base de datos entregado

Se generó una exportación SQL de la **base configurada actualmente en el entorno local**, PostgreSQL `sigedtec` en `127.0.0.1:5433`. El archivo contiene esquema y datos, incluidas las tablas de aplicación y de soporte; no incluye el archivo `.env`, claves de aplicación ni archivos subidos al almacenamiento.

Archivo generado: `database/exports/sigedtec_20261113_101903.sql`. Se restauró en una base PostgreSQL temporal aislada para comprobar que el respaldo se puede importar; la verificación encontró las 17 tablas públicas y el espacio temporal se eliminó después.

> **Confidencialidad:** el respaldo contiene datos reales de cuentas, inventario y solicitudes. Guárdelo con acceso restringido y no lo publique ni lo envíe a terceros. Incluye hashes de contraseñas, no las contraseñas en texto claro.

Para restaurarlo, crea primero una base PostgreSQL vacía y ejecuta desde PowerShell, adaptando host, puerto, usuario y nombre:

```powershell
createdb -h 127.0.0.1 -p 5433 -U postgres sigedtec_restaurada
psql -h 127.0.0.1 -p 5433 -U postgres -d sigedtec_restaurada -f "database\exports\sigedtec_20261113_101903.sql"
```

La restauración debe hacerse en una base vacía, no sobre la base en uso. `pg_dump` se ejecuta con `--no-owner --no-acl` para que el archivo no dependa de propietarios/permisos específicos del servidor de origen.

## 8. Notas de instalación y mantenimiento

- Mantener en privado `.env`; contiene configuración de base de datos y clave de aplicación.
- Tras recibir una versión del código, instalar dependencias según `composer.lock` y `package-lock.json`, compilar los recursos con `npm run build` y ejecutar `php artisan migrate` después de revisar las migraciones.
- Ejecutar `php artisan test` para la suite automatizada.
- Un respaldo SQL no contiene el código, dependencias, recursos subidos ni configuración secreta: preservar esos elementos por separado.
- La conexión y credenciales de restauración dependen del entorno donde se instale SIGEDTEC.
