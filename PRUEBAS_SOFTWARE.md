# Pruebas de software realizadas

## Prueba de unidad

Se realizo una prueba de unidad sobre el servicio `TransporteAccesoService`, encargado de la logica de acceso y avance de estados del transporte.

La prueba valida que:

- Los codigos de transporte se normalicen correctamente aunque tengan espacios, guiones o minusculas.
- El hash del codigo sea igual aunque el usuario escriba el codigo con formatos distintos.
- El flujo de transporte de productos organicos avance en el orden esperado.
- El flujo de alquiler de maquinaria use correctamente los estados de recogida, entrega y retorno.

Comando usado:

```bash
php artisan test --testsuite=Unit
```

Criterio de aprobacion: todos los casos de prueba unitaria deben pasar sin errores.

Resultado obtenido: 5 pruebas pasaron correctamente, con 13 aserciones exitosas.

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
