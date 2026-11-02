# Frontend - SIGEDTEC

Llevar control de las vistas, componentes, layout y lo que falta por mostrar correctamente.

## Estado actual

- Breeze con Blade + Tailwind
- Vite configurado
- Diseño con layout de app/guest
- Componentes UI básicos

## Vistas existentes

| Vista | Estado | Comentario |
| :-- | :--- | :--- |
| auth/login.blade.php | Presente | Funciona |
| auth/register.blade.php | Presente | Funciona con selección de oficina |
| layouts/app.blade.php | Presente | Layout principal |
| layouts/guest.blade.php | Presente | Layout para auth |
| dashboard/admin.blade.php | Presente | Revisar que muestre datos reales |
| dashboard/solicitante.blade.php | Presente | Revisar que muestre datos reales |
| oficinas/index.blade.php | Funcional | Listado, crear, editar, activar/desactivar |
| categorias/index.blade.php | Funcional | Listado, crear, editar, activar/desactivar |
| profile/* | Presente | Editar perfil |

## Componentes

Hay carpeta `components/ui/` con varios componentes reutilizables. Se deben seguir usando para mantener el mismo estilo.

## Lo que sigue

1. Completar vistas para oficinas (crear/editar)
2. Vistas para categorías
3. Vistas para inventario
4. Vistas para solicitudes (crear/listar/ver)
5. Vistas para aprobación/despacho
6. Vistas para reportes
7. Ajustar dashboard para que se vea claro por rol

## Notas

- Todo en español
- Mantener el mismo layout en todas las pantallas autenticadas
- Evitar duplicar estilos
- Usar los componentes `x-ui.*` cuando existan
- Pensado para desktop/tablet/móvil
