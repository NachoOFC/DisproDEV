Cambios en la barra lateral (estética)

Archivos editados:
- resources/views/compass/menu.blade.php

Resumen:
- Se actualizaron estilos inline para que cada `nav-item` y `dropdown-item` aparezcan como cuadros blancos (tarjetas) con borde redondeado y sombra sutil.
- No se cambió ninguna lógica JavaScript o rutas. Solo estética visual.
- Se mantuvo el fondo blanco global según la petición.

Cómo ver los cambios localmente:
1. Asegúrate de correr tu servidor local (php artisan serve) o usar tu entorno usual.
2. Limpiar caches si es necesario: `php artisan view:clear; php artisan cache:clear; php artisan config:clear`.
3. Abrir la app en el navegador y navegar a una página con el menú lateral (usuario con permisos apropiados).

Siguientes pasos opcionales:
- Mover estas reglas CSS a `resources/sass` (SASS) y compilar assets con `npm run dev` o `npm run production` para mantener el proyecto ordenado.
- Ajustar espaciados, sombras y tamaños desde variables SASS si se desea.

Revertir los cambios:
- Reemplaza `resources/views/compass/menu.blade.php` por la versión anterior desde control de versiones (git) o restáuralo desde tu backup si fuese necesario.

Contacto:
- Si quieres, puedo crear un commit con estos cambios y abrir un PR en la rama `feature/estetica` o mover los estilos a SASS y compilar assets.
