# Frontend - SIGEDTEC

Llevar control de las vistas, componentes, layout y lo que falta por mostrar correctamente.

## Estado actual

- Breeze con Blade + Tailwind 3 y Vite
- Tokens en `tailwind.config.js`: `primary`, `navy`, `page`, `surface`, `ink`, `muted`, `line`, sombras `card`/`panel`/`nav` y tipografía Inter
- Clases de componente en `resources/css/app.css`: `btn` (variantes), `field`, `field-select`, `field-area`, `panel`, `filter-bar`, `table-wrap`/`table-ui`, `empty-state`, `page-title`, `page-sub`
- Componentes `x-ui.*`: `icon`, `badge`, `alert`, `hero`, `tech-scene`, `page-header`
- Portada pública con `x-layouts.site` y `home.blade.php`; `/` muestra la portada a invitados y redirige a `dashboard` con sesión
- Sidebar navy con submenús, topbar con buscador (solo admin), campana de pendientes y menú de usuario; “Mi perfil” y “Cerrar sesión” viven en ese menú

## Vistas existentes

| Vista | Estado | Comentario |
| :-- | :--- | :--- |
| home.blade.php | Funcional | Portada pública con hero, características y llamada a la acción |
| components/layouts/site.blade.php | Funcional | Nav y pie institucional de la portada |
| auth/login.blade.php | Funcional | Tema oscuro con tarjetas de rol e inicio de sesión |
| auth/register.blade.php | Funcional | Registro con selección de oficina |
| layouts/app.blade.php | Funcional | Layout autenticado (sidebar, topbar, alertas, pie) |
| layouts/guest.blade.php | Funcional | Layout para auth |
| dashboard/admin.blade.php | Funcional | Indicadores, últimas solicitudes, dona de inventario y categorías |
| dashboard/solicitante.blade.php | Funcional | Indicadores propios, solicitudes recientes y oficina |
| oficinas/* | Funcional | Listado, crear, editar, activar/desactivar |
| categorias/* | Funcional | Listado, crear, editar; incluye las especificaciones de la categoría |
| equipos/index.blade.php | Funcional | Listado con filtros, acciones por estado |
| equipos/create.blade.php | Funcional | Registro con especificaciones según la categoría elegida |
| equipos/edit.blade.php | Funcional | Edición con las mismas reglas de especificaciones |
| solicitudes/index.blade.php | Funcional | Listado de solicitudes del solicitante |
| solicitudes/create.blade.php | Funcional | Crear solicitud |
| solicitudes/show.blade.php | Funcional | Detalle con equipos asignados |
| despacho/index.blade.php | Funcional | Bandeja con pestañas por estado |
| despacho/aprobar.blade.php | Funcional | Selección de equipos para aprobar |
| despacho/rechazar.blade.php | Funcional | Rechazo con observación |
| reportes/* | Funcional | Inventario, solicitudes, asignaciones y movimientos |
| usuarios/* | Funcional | Listado y cambio de rol |
| profile/* | Funcional | Datos, contraseña y eliminación de cuenta, en español |

## Componentes

Carpeta `components/ui/` con los componentes reutilizables del sistema visual. Toda pantalla nueva debe usar `x-ui.page-header`, `x-ui.badge`, `x-ui.alert` y las clases `panel`/`table-ui` antes que escribir utilidades sueltas.

## Lo que sigue

1. Nada pendiente de diseño: los módulos usan el mismo sistema visual.
2. Mejoras opcionales: paginar el listado de usuarios y revisar el resultado en navegador.

## Notas

- Todo en español
- Mantener el mismo layout en todas las pantallas autenticadas
- Evitar duplicar estilos
- Usar los componentes `x-ui.*` cuando existan
- Pensado para desktop/tablet/móvil
- El formulario de equipo oculta las especificaciones que no aplican a la categoría; el servidor las descarta igualmente
