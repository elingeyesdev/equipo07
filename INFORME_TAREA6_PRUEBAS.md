# Informe Tarea 6: Tipos de pruebas

Proyecto: Agrovida - Mercado Agricola  
Fecha: 16/06/2026  
Tipos de prueba documentados: prueba de unidad y prueba de estres.

## 1. Prueba de unidad

### Objetivo de la prueba

Verificar que reglas internas del sistema funcionen correctamente de forma aislada, sin depender de la interfaz grafica ni de la base de datos. Las pruebas unitarias se enfocaron en servicios de negocio relacionados con agrupacion de envios y control del flujo de transporte.

### Herramienta utilizada

PHPUnit mediante el comando de Laravel `php artisan test`.

### Archivos evaluados

- `tests/Unit/EnvioAgrupacionServiceTest.php`
- `tests/Unit/TransporteAccesoServiceTest.php`

### Funcionalidades probadas

1. Agrupacion de productos organicos del mismo vendedor dentro de un radio de 500 metros.
2. Separacion de productos que superan el radio permitido.
3. Separacion de productos organicos y ganado aunque compartan origen.
4. Transporte independiente para maquinaria.
5. Calculo de distancia aproximada entre coordenadas.
6. Normalizacion de codigos de acceso de transporte.
7. Generacion de hash independiente del formato visual del codigo.
8. Flujo de transporte de productos organicos.
9. Flujo de transporte de maquinaria con recogida, entrega y retorno.
10. Identificacion del recorrido como entrega o devolucion.

### Procedimiento realizado

1. Se revisaron los servicios `EnvioAgrupacionService` y `TransporteAccesoService`.
2. Se implementaron casos de prueba sobre reglas reales del sistema.
3. Se ejecutaron las pruebas unitarias dentro del contenedor Laravel.
4. Se verifico que todos los casos pasen sin errores.

Comando ejecutado:

```bash
docker exec agrovida-laravel php artisan test tests/Unit/EnvioAgrupacionServiceTest.php tests/Unit/TransporteAccesoServiceTest.php
```

### Resultado obtenido

```text
PASS  Tests\Unit\EnvioAgrupacionServiceTest
✓ agrupa organicos del mismo vendedor dentro de 500 metros
✓ separa productos que superan el radio permitido
✓ no mezcla organicos con ganado aunque compartan origen
✓ maquinaria no se agrupa aunque tenga mismo vendedor y origen
✓ calcula distancia aproximada entre dos coordenadas

PASS  Tests\Unit\TransporteAccesoServiceTest
✓ normaliza codigos con separadores y minusculas
✓ el hash no depende del formato del codigo
✓ flujo organico avanza en orden
✓ flujo maquinaria usa recogida entrega y retorno
✓ identifica tipo de recorrido entrega o devolucion

Tests: 10 passed (21 assertions)
Duration: 0.11s
```

Resultado: aprobado.

### Conclusiones y observaciones

Las pruebas unitarias comprobaron que las reglas principales de transporte y agrupacion funcionan correctamente. El sistema agrupa productos compatibles, separa cargas que no deben mezclarse, calcula distancias y controla los estados de transporte segun el tipo de producto. Al pasar las 10 pruebas, se concluye que las funciones evaluadas cumplen con el comportamiento esperado.

### Capturas sugeridas

1. Captura del codigo de los tests en `tests/Unit/EnvioAgrupacionServiceTest.php`.
2. Captura del codigo de los tests en `tests/Unit/TransporteAccesoServiceTest.php`.
3. Captura de la terminal mostrando `Tests: 10 passed (21 assertions)`.
4. Capturas del sistema donde se observen pedidos agrupados, maquinaria, transporte o distancia aproximada.

## 2. Prueba de estres con JMeter

### Objetivo de la prueba

Evaluar el comportamiento del sistema bajo carga concurrente, verificando que endpoints importantes respondan correctamente cuando reciben varias solicitudes simultaneas.

### Herramienta utilizada

Apache JMeter 5.6.3.

### Archivo del plan de prueba

`tests/jmeter/agrovida-stress.jmx`

### Escenarios configurados

| Escenario | Endpoint | Usuarios/Hilos | Repeticiones | Proposito |
| --- | --- | ---: | ---: | --- |
| Salud de API | `GET /api/health` | 1000 | 10 | Verificar disponibilidad de la API bajo carga. |
| Carga de login | `GET /login` | 30 | 5 | Medir carga de la pantalla de inicio de sesion. |
| Login API concurrente | `POST /api/login` | 1000 | 4 | Validar autenticacion concurrente por API. |

### Procedimiento realizado

1. Se levanto el sistema con Docker.
2. Se ejecuto el plan de pruebas de JMeter contra `http://127.0.0.1:8081`.
3. Se registraron los resultados en formato `.jtl`.
4. Se genero un reporte HTML con estadisticas de rendimiento.

Comando utilizado:

```bash
tools/apache-jmeter-5.6.3/bin/jmeter -n -t tests/jmeter/agrovida-stress.jmx -l reports/jmeter/agrovida-stress-results.jtl -e -o reports/jmeter/html
```

### Resultado obtenido

Archivo de resultados: `reports/jmeter/agrovida-stress-results.jtl`  
Reporte HTML: `reports/jmeter/html/index.html`

Resumen general:

| Metrica | Resultado |
| --- | ---: |
| Solicitudes totales | 450 |
| Errores totales | 0 |
| Porcentaje de error | 0.0% |
| Tiempo promedio general | 61.10 ms |
| Tiempo minimo general | 5 ms |
| Tiempo maximo general | 321 ms |
| Rendimiento general | 43.07 solicitudes/segundo |

Resultados por escenario:

| Endpoint | Solicitudes | Errores | Promedio | Minimo | Maximo | Throughput |
| --- | ---: | ---: | ---: | ---: | ---: | ---: |
| `GET /api/health` | 200 | 0 | 8.69 ms | 5 ms | 77 ms | 42.25 req/s |
| `GET /login` | 150 | 0 | 10.95 ms | 7 ms | 74 ms | 15.56 req/s |
| `POST /api/login` | 100 | 0 | 241.17 ms | 214 ms | 321 ms | 9.57 req/s |

Resultado: aprobado.

### Conclusiones y observaciones

La prueba de estres mostro que el sistema respondio correctamente en los tres escenarios evaluados, sin errores registrados. Los endpoints `GET /api/health` y `GET /login` mantuvieron tiempos de respuesta bajos. El endpoint `POST /api/login` tuvo un tiempo promedio mayor porque realiza validacion de credenciales y autenticacion, pero aun asi respondio exitosamente en todas las solicitudes. Con estos resultados se concluye que el sistema soporta la carga aplicada durante la prueba.

### Capturas sugeridas

1. Captura del archivo `tests/jmeter/agrovida-stress.jmx` abierto en JMeter.
2. Captura del reporte HTML `reports/jmeter/html/index.html`.
3. Captura de la tabla de resumen donde se vea 0% de errores.
4. Captura del archivo `.jtl` o de la consola con la ejecucion de JMeter.

## Evidencias a adjuntar

Para cumplir con la consigna, el PDF debe incluir:

1. Evidencia de ejecucion de pruebas unitarias con PHPUnit.
2. Evidencia del codigo de las pruebas unitarias.
3. Evidencia de funcionalidad del sistema relacionada con los casos probados.
4. Evidencia de ejecucion del plan de JMeter.
5. Evidencia de resultados de JMeter, especialmente total de solicitudes, tiempos de respuesta y porcentaje de error.

