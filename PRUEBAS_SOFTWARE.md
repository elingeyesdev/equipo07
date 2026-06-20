# Pruebas de software realizadas

## Pruebas unitarias

Se implementaron pruebas unitarias con PHPUnit sobre servicios reales del sistema Laravel:

- `App\Services\TransporteAccesoService`: valida reglas de negocio para codigos de transporte, flujo de estados, activacion GPS y tipo de recorrido.
- `App\Services\EnvioAgrupacionService`: valida agrupacion de productos por vendedor, tipo de carga, distancia, direccion y coordenadas de origen.

### Funcionalidades evaluadas

| Nro. | Funcionalidad evaluada | Objetivo e impacto | Resultado esperado |
| --- | --- | --- | --- |
| 1 | Normalizacion de codigos de transporte | Asegurar que el codigo pueda ingresarse con espacios, guiones o minusculas sin afectar la busqueda. | El codigo se guarda en mayusculas y sin separadores. |
| 2 | Hash de codigo de transporte | Evitar fallos de acceso cuando el usuario escribe el mismo codigo con otro formato. | Dos formatos equivalentes generan el mismo hash. |
| 3 | Flujo de transporte para organicos | Verificar que los productos organicos avancen por el flujo correcto de entrega. | Preparando avanza a en camino y luego a esperando confirmacion. |
| 4 | Flujo de transporte para maquinaria | Validar el recorrido completo de recogida, entrega y retorno de maquinaria alquilada. | Cada estado avanza al siguiente estado esperado. |
| 5 | Estados disponibles para organicos | Evitar que productos organicos muestren estados de retorno de maquinaria. | El catalogo no contiene `en_camino_retorno`. |
| 6 | Estados disponibles para maquinaria | Confirmar que maquinaria use el catalogo completo de transporte. | El catalogo incluye estados de retorno. |
| 7 | Activacion GPS en organicos | Confirmar que el GPS solo se active mientras el envio necesita seguimiento. | Se activa en `preparando` y se desactiva en `entregado`. |
| 8 | Activacion GPS en maquinaria | Confirmar que el GPS se desactive cuando el retorno ya finalizo. | Se activa durante recogida y se desactiva en `devuelto_vendedor`. |
| 9 | Tipo de recorrido | Diferenciar rutas de entrega y devolucion para mostrar el tracking correcto. | `en_camino_entrega` es entrega y `en_camino_retorno` es devolucion. |
| 10 | Agrupacion de organicos cercanos | Reducir envios cuando productos del mismo vendedor salen de puntos cercanos. | Productos dentro de 500 metros comparten grupo. |
| 11 | Separacion por distancia | Evitar agrupar productos demasiado alejados. | Productos fuera del radio generan grupos distintos. |
| 12 | Separacion por tipo de carga | Evitar mezclar organicos y ganado en un mismo transporte. | Tipos de carga diferentes generan grupos distintos. |
| 13 | Agrupacion por direccion normalizada | Permitir agrupar sin coordenadas cuando la direccion escrita representa el mismo origen. | Direcciones equivalentes comparten grupo. |
| 14 | Separacion por vendedor | Evitar unir productos de vendedores distintos aunque compartan origen. | Vendedores distintos generan grupos distintos. |
| 15 | Maquinaria en grupos independientes | Evitar compartir traslado de maquinaria aunque tenga igual origen. | Cada maquinaria genera su propio grupo. |
| 16 | Calculo de distancia | Validar la formula usada para comparar origenes. | El mismo punto devuelve distancia 0. |
| 17 | Datos de origen del grupo | Confirmar que el resultado conserve direccion y coordenadas del origen. | La agrupacion devuelve direccion, latitud y longitud correctas. |

Comando usado:

```bash
php artisan test --testsuite=Unit
```

Criterio de aprobacion: todos los casos de prueba unitaria deben pasar sin errores.

Resultado obtenido: 18 pruebas pasaron correctamente, con 33 aserciones exitosas.

### Evidencias requeridas

Por cada funcionalidad evaluada se deben tomar:

- Captura de la funcionalidad funcionando en el sistema.
- Captura del codigo de la prueba unitaria correspondiente.
- Captura de la ejecucion exitosa del comando `php artisan test --testsuite=Unit`.
- Breve descripcion usando la informacion de la tabla anterior.

## Prueba de estres con JMeter

Se realizo una prueba de estres usando Apache JMeter 5.6.3. El plan de pruebas esta en `tests/jmeter/agrovida-stress.jmx`.

La prueba incluyo tres escenarios:

- `GET /api/health`: valida que la API responda correctamente bajo carga.
- `GET /login`: simula usuarios cargando la pantalla de inicio de sesion.
- `POST /api/login`: simula usuarios realizando login concurrente por API con credenciales validas.

La prueba midio:

- Cantidad total de solicitudes por escenario.
- Respuestas exitosas y fallidas.
- Tiempo minimo, promedio y maximo de respuesta.
- Rendimiento general en solicitudes por segundo.

Comando usado:

```bash
tools/apache-jmeter-5.6.3/bin/jmeter -n -t tests/jmeter/agrovida-stress.jmx -l reports/jmeter/agrovida-stress-results.jtl -e -o reports/jmeter/html
```

Criterio de aprobacion: la prueba se considera aprobada si las solicitudes responden correctamente y no existen errores.

Resultado obtenido:

- Solicitudes totales: 450.
- Errores totales: 0.
- Rendimiento general: 42.6 solicitudes por segundo.
- `GET /api/health`: 200 solicitudes, 0 errores, promedio 8.69 ms, minimo 5 ms, maximo 77 ms.
- `GET /login`: 150 solicitudes, 0 errores, promedio 10.95 ms, minimo 7 ms, maximo 74 ms.
- `POST /api/login`: 100 solicitudes, 0 errores, promedio 241.17 ms, minimo 214 ms, maximo 321 ms.

JMeter tambien genero un reporte HTML en `reports/jmeter/html/index.html`.
