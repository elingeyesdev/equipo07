# Resumen de sesion: tracking en tiempo real y rol transportista

Fecha de trabajo: 7 de junio de 2026

## Objetivo trabajado

Se avanzo el modulo de seguimiento de pedidos/alquileres para que funcione con un rol separado de transportista. La idea principal fue que el vendedor ya no maneje el recorrido, sino que solo acepte la solicitud, asigne un transportista y finalice cuando corresponda.

## Cambios implementados

### Tracking GPS web

- Se agrego seguimiento GPS para solicitudes aceptadas.
- El transportista puede abrir una pantalla GPS desde su panel.
- La ubicacion se envia al backend y se guarda en la tabla `pedido_ubicaciones`.
- Se muestra la ruta sugerida y el recorrido real en mapa.
- Se recalcula la ruta hacia el objetivo actual segun el estado del transporte.
- Se agregaron iconos diferenciados en el mapa:
  - Azul con camion: transportista.
  - Naranja con caja: producto o punto del vendedor.
  - Verde con casa: destino del comprador.
  - Morado con almacen: retorno al vendedor.
  - Linea verde: ruta sugerida.
  - Linea azul: recorrido real.

### Flujo de transporte

Se agregaron estados de transporte para representar el recorrido:

- Transportista asignado.
- En camino a recoger.
- Llego al punto de recogida.
- Producto recogido.
- En camino al comprador.
- Llego al destino.
- Esperando confirmacion del comprador.
- Entregado al comprador.
- Devolucion solicitada.
- En camino a recoger devolucion.
- Llego a recoger devolucion.
- Maquinaria recogida para retorno.
- En camino al punto de retorno.
- Llego al punto de retorno.
- Devuelto al vendedor.

### Validacion por distancia

- El sistema valida que el transportista este cerca del punto correspondiente antes de marcar llegada.
- Por ahora el radio permitido es de 100 metros.
- Se valida llegada al punto de recogida, llegada al destino, recogida de devolucion y retorno.

### Confirmacion del comprador

- Cuando el transportista llega al destino, el pedido queda esperando confirmacion.
- El comprador confirma recepcion desde su vista de pedido.
- Para venta normal, la confirmacion finaliza la venta.
- Para maquinaria, la confirmacion deja el alquiler en uso hasta que exista devolucion.

### Rol transportista

- Se creo el rol `transportista`.
- Se agrego `transportista_id` a `pedido_detalles`.
- Se agrego middleware para proteger rutas de transportista.
- El transportista solo puede ver sus envios asignados.
- El transportista no ve carrito, mis pedidos, catalogo ni opciones de vendedor.
- Si intenta entrar a rutas que no le corresponden, se redirige a sus envios.

### Panel de transportista

Se agregaron pantallas para transportista:

- Lista de envios asignados.
- Detalle del envio.
- Pantalla GPS del envio.

Desde ahi el transportista puede:

- Ver el envio asignado.
- Abrir el GPS.
- Iniciar recorrido.
- Enviar ubicacion.
- Avanzar los estados del transporte.

### Admin puede asignar rol transportista

Se agrego una vista de admin para gestionar transportistas:

- Ruta: `/admin/transportistas`
- El admin puede buscar usuarios.
- El admin puede convertir un usuario en transportista.
- El admin puede quitar el rol transportista y devolverlo a cliente.

### Vendedor queda limitado

El vendedor ahora:

- Acepta o rechaza solicitudes.
- Decide a quien vender o alquilar.
- Asigna transportista.
- Finaliza solo cuando el flujo ya lo permite.

El vendedor ya no:

- Avanza estados del transporte.
- Marca maquinaria como devuelta.
- Maneja el recorrido GPS.
- Cambia el transportista cuando el recorrido ya inicio.

## Archivos principales agregados

- `app/Http/Controllers/AdminTransportistaController.php`
- `app/Http/Controllers/PedidoUbicacionController.php`
- `app/Http/Controllers/TransportistaEnvioController.php`
- `app/Http/Middleware/NoTransportista.php`
- `app/Http/Middleware/RoleTransportista.php`
- `app/Models/PedidoUbicacion.php`
- `resources/views/admin/transportistas/index.blade.php`
- `resources/views/transportista/envios/index.blade.php`
- `resources/views/transportista/envios/show.blade.php`
- `resources/views/vendedor/solicitudes/tracking.blade.php`
- Migraciones `2026_06_07_000001` a `2026_06_07_000006`.

## Archivos principales modificados

- `routes/web.php`
- `bootstrap/app.php`
- `app/Models/PedidoDetalle.php`
- `app/Models/Pedido.php`
- `app/Models/Role.php`
- `app/Models/User.php`
- `app/Http/Controllers/PedidoController.php`
- `app/Http/Controllers/VendedorSolicitudController.php`
- `app/Http/Controllers/HomeController.php`
- `database/seeders/RoleSeeder.php`
- `resources/views/layouts/adminlte.blade.php`
- `resources/views/pedidos/show.blade.php`
- `resources/views/vendedor/solicitudes/index.blade.php`
- `resources/views/vendedor/solicitudes/show.blade.php`

## Que hay por ahora

- Tracking GPS desde navegador.
- Funciona con HTTPS usando ngrok.
- Permite probar con un telefono real.
- Permite que un transportista lejano entre con URL de ngrok.
- Vendedor y comprador pueden ver el avance desde la web.
- Hay separacion de roles: vendedor, comprador, admin y transportista.
- El admin puede crear transportistas desde el panel.

## Que no hay por ahora

- No hay app movil nativa todavia.
- El GPS web depende de que Chrome mantenga la pagina abierta.
- En segundo plano o con pantalla bloqueada, el navegador puede pausar el envio.
- No hay API movil final para transportistas.
- No hay token/API con Laravel Sanctum todavia.
- No hay APK ni app instalable.
- No hay despliegue permanente en servidor.

## Idea final conversada: app movil solo para transportista

La mejor evolucion seria crear una app movil minima solo para transportistas.

La web actual quedaria para:

- Admin.
- Vendedor.
- Comprador.

La app movil quedaria para:

- Login del transportista.
- Ver envios asignados.
- Ver detalle del envio.
- Iniciar seguimiento.
- Enviar ubicacion por GPS.
- Avanzar estados del transporte.
- Mostrar mapa y objetivo actual.

## Necesidad de API

Para la app movil se necesita exponer endpoints JSON desde Laravel. Ejemplo:

- `POST /api/transportista/login`
- `GET /api/transportista/envios`
- `GET /api/transportista/envios/{id}`
- `POST /api/transportista/envios/{id}/ubicacion`
- `POST /api/transportista/envios/{id}/estado`
- `POST /api/transportista/logout`

La app enviaria ubicaciones asi:

```json
{
  "latitud": -17.7831,
  "longitud": -63.1822,
  "precision_metros": 8,
  "velocidad_m_s": 4.2,
  "rumbo_grados": 120
}
```

Laravel guardaria esos datos en `pedido_ubicaciones` y la web seguiria mostrando el mapa.

## Ngrok y funcionamiento remoto

Para que alguien lejos pueda usar la app o la web mientras el proyecto esta local:

- La laptop debe estar encendida.
- Docker/Laravel debe estar corriendo.
- Ngrok debe estar corriendo.
- La app o el navegador deben usar la URL de ngrok.

Flujo:

```text
Celular transportista -> URL ngrok -> Laravel local -> Base de datos local
```

Para empresa real o despliegue serio:

- Laravel debe subirse a un servidor.
- Se debe usar dominio con HTTPS.
- La app debe apuntar a ese dominio, no a localhost ni ngrok.

## Comandos de verificacion usados

```bash
docker exec agrovida-laravel php artisan migrate
docker exec agrovida-laravel php artisan view:cache
docker exec agrovida-laravel php artisan test
```

Resultado: las vistas compilaron y las pruebas basicas pasaron.
