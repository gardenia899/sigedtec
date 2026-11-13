# Interfaz de SIGEDTEC

La interfaz está escrita en español con vistas Blade, Tailwind CSS y componentes reutilizables. Vite compila los recursos del frontend y Alpine.js apoya interacciones puntuales. Las pantallas autenticadas comparten el layout principal, con navegación lateral y barra superior; el contenido del menú depende del rol.

## Vistas principales

| Carpeta o vista | Función |
| --- | --- |
| `home.blade.php` y `components/layouts/site.blade.php` | Portada pública; una persona autenticada continúa al panel. |
| `auth/` | Inicio de sesión, registro y recuperación de acceso. |
| `layouts/app.blade.php` | Navegación autenticada, avisos, campana de solicitudes y menú de cuenta. |
| `dashboard/` | Paneles con indicadores y accesos según el perfil. |
| `oficinas/` | Consulta, alta y edición de oficinas. |
| `categorias/` | Consulta y edición de categorías y sus campos técnicos. |
| `equipos/index.blade.php` y `equipos/edit.blade.php` | Inventario con filtros, acciones de estado y edición. El alta se presenta en un modal desde el listado. |
| `solicitudes/` | Listado propio, creación y detalle de solicitudes. |
| `despacho/` | Bandeja del administrador, selección de equipos para aprobar o formulario de rechazo. |
| `reportes/` | Cuatro reportes web y plantilla compartida para exportación PDF. |
| `usuarios/` | Consulta y cambio de rol por parte del administrador. |
| `profile/` | Edición de datos de perfil y cuenta. |

## Acciones visibles

- **Inventario:** filtrar y limpiar filtros; abrir el formulario modal de alta; editar; dar de baja un equipo permitido o restaurarlo desde la vista de bajas. El código patrimonial se muestra, pero no se edita.
- **Solicitudes:** crear, consultar el detalle y cancelar una solicitud todavía pendiente. El solicitante solo ve sus propias solicitudes.
- **Bandeja de administración:** filtrar por estado, consultar el detalle, aprobar con selección de los equipos disponibles que correspondan o rechazar con una observación.
- **Catálogos:** crear y editar oficinas y categorías, activar o desactivar registros. Los códigos de oficina son generados automáticamente.
- **Reportes:** filtrar, limpiar filtros, paginar y descargar en PDF los resultados filtrados.
- **Campana:** consultar solicitudes recientes del perfil; sus enlaces llevan al detalle o al procesamiento correspondiente.
- **Perfil:** abrir opciones de cuenta desde el menú superior.

## Componentes y estilos

Los componentes Blade compartidos están en `resources/views/components`, incluidos `x-ui.*`. Los estilos y clases comunes se mantienen en `resources/css/app.css`; la configuración de Tailwind está en los archivos de configuración de la raíz.

Para compilar los recursos antes de servir la aplicación:

```powershell
npm install
npm run build
```

Durante el desarrollo puede ejecutarse `npm run dev`. Para compilar con Vite no se requiere abrir manualmente cada vista.
