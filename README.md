# SIGEDTEC

SIGEDTEC es una aplicación web para llevar el inventario de equipos tecnológicos y organizar las solicitudes y entregas de una institución. Reúne en un mismo lugar los catálogos, el seguimiento de cada solicitud, el historial de movimientos y los reportes.

El sistema está desarrollado en español. Lo utiliza el equipo de Soporte Técnico para administrar el inventario y procesar solicitudes, y los jefes de oficina para pedir equipos y consultar el estado de sus solicitudes.

**Desarrollado por:** Yocelianna Rodriguez

## Qué permite hacer

- Registrar oficinas, categorías y equipos; los códigos de oficina y patrimoniales se generan desde el sistema.
- Definir qué características técnicas se solicitan según el tipo de equipo.
- Crear solicitudes indicando categoría, cantidad y justificación.
- Revisar, aprobar o rechazar solicitudes. Al aprobar, el administrador selecciona equipos disponibles concretos.
- Solicitar la devolución de equipos asignados y permitir que Soporte Técnico confirme su estado antes de liberarlos.
- Mantener una bitácora de cambios de estado y un historial de asignaciones.
- Consultar reportes de inventario, solicitudes, asignaciones y movimientos, y descargarlos en PDF.
- Trabajar con dos perfiles: `admin` (Soporte Técnico) y `solicitante` (Jefe de Oficina).

## Tecnologías

| Parte | Tecnología |
| --- | --- |
| Servidor | PHP 8.2 o superior y Laravel 12 |
| Base de datos | PostgreSQL |
| Vistas | Blade y HTML |
| Estilos e interacción | Tailwind CSS, JavaScript y Alpine.js |
| Recursos del frontend | Vite y npm |
| Reportes PDF | Dompdf |
| Pruebas | PHPUnit mediante Artisan |

Es una aplicación monolítica renderizada por Laravel; los flujos principales no dependen de una SPA ni de una API independiente. En el entorno de desarrollo usado para verificar el proyecto se ejecutaron PHP 8.4.22, Laravel 12.70.2, PostgreSQL 18, Node.js 24 y Vite 7. Las versiones compatibles se definen en `composer.json` y `package.json`.

## Preparar el entorno local

En cada PC se necesita PHP 8.2 o superior con las extensiones requeridas por Laravel y `pdo_pgsql` habilitada para el PHP de la terminal, Composer, Node.js/npm y un servidor PostgreSQL instalado y en ejecución. pgAdmin 4 sirve para administrar el servidor, pero no lo instala ni lo reemplaza. El puerto PostgreSQL habitual es `5432`; se debe usar el puerto configurado en ese PC. En PowerShell, desde la carpeta del proyecto:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

En pgAdmin 4, conéctate al servidor PostgreSQL de ese PC y crea una base de datos vacía llamada `sigedtec`. Edita `.env` y configura `DB_CONNECTION=pgsql`, `DB_HOST=127.0.0.1`, `DB_PORT` (el puerto del servidor, normalmente `5432`), `DB_DATABASE=sigedtec`, `DB_USERNAME` y `DB_PASSWORD` (las credenciales de PostgreSQL de ese PC). No copies el `.env` ni credenciales reales al repositorio o al pendrive: cada instalación debe usar sus propias credenciales. Las migraciones crean las tablas dentro de la base existente; no crean la base de datos.

Después de configurar la conexión:

```powershell
php artisan config:clear
php artisan migrate
npm run build
php artisan serve
```

Abre la dirección que indique Artisan, normalmente `http://127.0.0.1:8000`. Para trabajar con Vite en modo desarrollo, usa `npm run dev` en otra terminal. El catálogo inicial de categorías se puede cargar con `php artisan db:seed --class=CategorySeeder`.

Las migraciones modifican la base de datos seleccionada en `.env`; comprueba la conexión y el nombre de la base antes de ejecutarlas. No uses `migrate:fresh` en una base con datos que deban conservarse. Las pruebas utilizan la configuración de base de datos definida en `phpunit.xml`; confirma que esa base sea de pruebas antes de ejecutar la suite.

## Perfiles y recorrido de trabajo

1. Soporte Técnico configura oficinas y categorías, y registra los equipos disponibles o en mantenimiento.
2. El jefe de oficina crea una solicitud. La oficina asociada a su cuenta se incluye automáticamente.
3. Soporte Técnico revisa la petición. Para aprobarla, selecciona equipos disponibles de la categoría solicitada; también puede rechazarla con una observación.
4. Al aprobar, el sistema vuelve a verificar la disponibilidad y registra la asignación en una transacción.
5. El solicitante consulta la decisión y los equipos asignados. El administrador puede revisar movimientos y exportar reportes.

El acceso a las pantallas y las operaciones se valida en el servidor según el perfil; no depende únicamente de ocultar opciones en el menú.

## Organización de los datos

PostgreSQL almacena usuarios y oficinas, categorías, equipos, solicitudes, equipos asignados a cada solicitud, cambios de estado y secuencias para generar códigos. Las migraciones en `database/migrations` definen la estructura y restricciones. Los equipos dados de baja se conservan mediante borrado lógico.

El archivo de configuración local `.env` no se publica. Los respaldos de la base de datos pueden contener información personal y hashes de contraseñas: deben manejarse de forma privada y no subirse a este repositorio.

## Pruebas y compilación

```powershell
php artisan test
npm run build
```

Para comprobar el formato de PHP con Laravel Pint:

```powershell
vendor/bin/pint --test
```

## Control de versiones y cambios

El repositorio remoto es [gardenia899/sigedtec](https://github.com/gardenia899/sigedtec). La rama de trabajo y publicación actual es `main`. El historial registra los cambios por medio de Git; no se presupone una versión de producto etiquetada ni un esquema de ramas GitFlow.

Para un cambio pequeño, se puede trabajar en una rama y abrir una revisión antes de integrarlo. Conviene usar mensajes breves que indiquen el propósito, por ejemplo `feat: agregar exportación de reportes` o `fix: validar disponibilidad al asignar equipos`. Antes de publicar, revisar las pruebas, el diff y que no se incluyan `.env`, respaldos ni archivos temporales. El registro de avances está en [docs/ORDEN_COMMITS.md](docs/ORDEN_COMMITS.md).

## Documentación

- [Manual completo de instalación y uso (Word)](docs/MANUAL_INSTALACION_Y_USO_SIGEDTEC.docx): preparación de Windows y PostgreSQL, migraciones, creación del administrador, módulos, respaldo y solución de problemas.
- [Guía técnica y de uso](docs/GUIA_TECNICA_Y_USO_SIGEDTEC.md): tecnologías, perfiles, vistas y acciones, flujo de trabajo y notas sobre el respaldo local.
- [Backend](docs/BACKEND.md): módulos del servidor, validaciones y persistencia.
- [Frontend](docs/FRONTEND.md): vistas, navegación y componentes de interfaz.
- [Diseño del sistema (SDD)](SDD.md): decisiones y especificaciones de diseño; algunas secciones describen el diseño previsto y no necesariamente la implementación actual. Para el estado vigente, consulta primero este README y las guías de backend y frontend.
- [Historial de commits](docs/ORDEN_COMMITS.md): seguimiento de cambios publicados.

## Licencia

El proyecto no declara una licencia de distribución propia. La licencia MIT de Laravel no determina por sí sola la licencia de SIGEDTEC.
