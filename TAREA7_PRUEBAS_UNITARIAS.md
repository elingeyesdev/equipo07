# Tarea 7: Implementacion de pruebas unitarias

Proyecto: Agrovida  
Tecnologia: Laravel + PHPUnit  
Fecha de ejecucion: 16/06/2026

## Comandos utilizados

```bash
docker compose up -d
docker exec agrovida-laravel php artisan test tests/Unit/EnvioAgrupacionServiceTest.php tests/Unit/TransporteAccesoServiceTest.php
```

## Resultado de ejecucion

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
Duration: 0.05s
```

## Pruebas implementadas

### 1. Agrupacion de organicos del mismo vendedor dentro de 500 metros

Funcionalidad evaluada: agrupacion automatica de productos organicos para transporte.

Objetivo de la prueba: verificar que dos productos organicos del mismo vendedor, ubicados dentro del radio permitido, compartan el mismo grupo de envio.

Resultado esperado: ambos items reciben el mismo `grupo_envio`.

Archivo: `tests/Unit/EnvioAgrupacionServiceTest.php`

### 2. Separacion de productos que superan el radio permitido

Funcionalidad evaluada: control de distancia maxima para agrupar envios.

Objetivo de la prueba: comprobar que productos ubicados a mas de 500 metros se asignen a grupos diferentes.

Resultado esperado: los items reciben `grupo_envio` diferente.

Archivo: `tests/Unit/EnvioAgrupacionServiceTest.php`

### 3. Separacion de organicos y ganado aunque compartan origen

Funcionalidad evaluada: reglas de agrupacion por tipo de carga.

Objetivo de la prueba: asegurar que productos organicos y ganado no se mezclen en un mismo envio aunque tengan el mismo vendedor y ubicacion.

Resultado esperado: se generan grupos de envio distintos.

Archivo: `tests/Unit/EnvioAgrupacionServiceTest.php`

### 4. Maquinaria no se agrupa aunque tenga el mismo vendedor y origen

Funcionalidad evaluada: regla especial de transporte de maquinaria.

Objetivo de la prueba: validar que cada maquinaria se transporte de forma independiente aunque coincida en origen y vendedor.

Resultado esperado: cada maquinaria recibe un `grupo_envio` distinto.

Archivo: `tests/Unit/EnvioAgrupacionServiceTest.php`

### 5. Calculo de distancia aproximada entre coordenadas

Funcionalidad evaluada: calculo de distancia entre el origen del producto y el destino del comprador.

Objetivo de la prueba: verificar que el sistema calcule una distancia aproximada en metros entre dos coordenadas geograficas.

Resultado esperado: la distancia calculada para dos puntos cercanos queda entre 100 y 120 metros.

Archivo: `tests/Unit/EnvioAgrupacionServiceTest.php`

### 6. Normalizacion de codigos de acceso

Funcionalidad evaluada: limpieza de codigos QR o codigos de acceso de transporte.

Objetivo de la prueba: confirmar que el sistema elimine espacios, guiones y convierta el codigo a mayusculas.

Resultado esperado: el codigo ` abcd-1234 efgh ` se normaliza como `ABCD1234EFGH`.

Archivo: `tests/Unit/TransporteAccesoServiceTest.php`

### 7. Hash independiente del formato del codigo

Funcionalidad evaluada: busqueda segura de accesos por codigo.

Objetivo de la prueba: validar que dos codigos con distinto formato visual generen el mismo hash.

Resultado esperado: `ABCD-1234-EFGH` y `abcd 1234 efgh` producen el mismo hash.

Archivo: `tests/Unit/TransporteAccesoServiceTest.php`

### 8. Flujo de transporte para productos organicos

Funcionalidad evaluada: avance de estados de transporte para productos organicos.

Objetivo de la prueba: comprobar que el flujo avance desde preparacion hasta espera de confirmacion.

Resultado esperado: `preparando` avanza a `en_camino_entrega` y luego a `esperando_confirmacion`.

Archivo: `tests/Unit/TransporteAccesoServiceTest.php`

### 9. Flujo de transporte para maquinaria

Funcionalidad evaluada: avance de estados en alquiler de maquinaria.

Objetivo de la prueba: validar el recorrido completo de recogida, entrega y retorno al vendedor.

Resultado esperado: la maquinaria avanza por los estados `en_camino_recogida`, `producto_recogido`, `en_camino_entrega`, `llego_destino`, `esperando_confirmacion`, `en_camino_retorno` y `devuelto_vendedor`.

Archivo: `tests/Unit/TransporteAccesoServiceTest.php`

### 10. Identificacion del tipo de recorrido: entrega o devolucion

Funcionalidad evaluada: clasificacion del recorrido de transporte para maquinaria.

Objetivo de la prueba: verificar que el sistema identifique si el transporte corresponde a una entrega al comprador o a una devolucion hacia el vendedor.

Resultado esperado: el estado `en_camino_entrega` se clasifica como `entrega`, mientras que `devolucion_solicitada` y `en_camino_retorno` se clasifican como `devolucion`.

Archivo: `tests/Unit/TransporteAccesoServiceTest.php`

## Evidencias sugeridas para el PDF

1. Captura del sistema funcionando: abrir `http://localhost:8081` y capturar una pantalla relacionada con pedidos, transporte o envios.
2. Captura del codigo: mostrar los archivos `tests/Unit/EnvioAgrupacionServiceTest.php` y `tests/Unit/TransporteAccesoServiceTest.php`.
3. Captura de ejecucion: mostrar la terminal con el comando de PHPUnit y el resultado `Tests: 10 passed (21 assertions)`.
