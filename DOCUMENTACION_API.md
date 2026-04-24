# Documentación Estructural del Proyecto - Equipo 07

Esta documentación al grano establece de forma técnica dónde se configuran e implementan las funcionalidades primordiales y lógicas expuestas del sistema. 

---

## 1. Operaciones Expuestas por API (Endpoints)
Estructura REST diseñada para la interacción asincrónica enviando y devolviendo datos en formato JSON sin usar interfaces integradas.

* **Ubicación de Rutas:** `routes/api.php`
* **Lógica (Controladores):** Carpeta `app/Http/Controllers/Api/`

**Endpoints Configurados:**
* `[GET, POST, PUT, DELETE]` **/api/organicos** - Operaciones CRUD para el módulo de ventas y catálogo orgánico.
* `[GET, POST, PUT, DELETE]` **/api/maquinarias** - Operaciones CRUD para el módulo de maquinaria pesada.
* `[GET, POST, PUT, DELETE]` **/api/ganados** - Operaciones CRUD para el catálogo de animales/ganadería.
* `[GET]` **/api/geocodificacion** - Endpoint utilitario que recibe coordenadas *(Lat/Lon)* y retorna información geográfica detallada de ubicación.
* `[GET]` **/api/categorias, /api/tipo-cultivos, ...** - Conjunto de endpoints de catálogo esenciales para desplegables y opciones.

---

## 2. Conexiones Frontend API
Mecanismos del sistema visual mediante el cual se conectan dinámicamente con los flujos de información remota de la propia API de backend.

* **Base estandarizada del Cliente:** `resources/js/bootstrap.js` incluye la inicialización de **Axios**, adaptado para todas las ventanas del frontend.
* **Punto de ejecución visual:** 
  * `resources/views/organicos/_form.blade.php` 
  * `resources/views/maquinarias/_form.blade.php`
* **Implementación Real:** A lo largo de la incrustación de mapas en los formularios anteriores, el JavaScript (del lado de la vista) se apoya en una llamada `fetch('/api/geocodificacion...')` que viaja a la API para resolver direcciones en base a clics del usuario en un mapa y rellenar inputs sin recargar la pantalla.

---

## 3. Adaptación Dinámica de Consultas a BD
Gestión inteligente donde en vez de efectuar un `SELECT` plano, la aplicación reconstruye el árbol SQL conforme los escenarios o requerimientos.

* **Ejemplo Neurálgico:** `app/Http/Controllers/ReporteController.php` *(métodos de ventas o productos estancados).*
* **Ejecución Técnica:**
  * **Modificadores condicionales:** El proyecto inyecta el método `$query->when(...)` del ORM Eloquent. Significa que partes del SQL *(como exclusiones WHERE)* solo nacen y ocurren si el usuario realmente habilitó un filtro desde la interfaz web o API.
  * **Cálculos nativos multi-universo:** Fusionan universos diferentes *(maquinaria, orgánicos, y animales)* usando sentencias complejas de Query Builder como `unionAll()` e insertan lógicas matemáticas directas en MySQL usando `DB::raw()`.

---

## 4. Consistencia Integral y Canal API Abierto (CORS)
Blindaje del sistema para no procesar imperfecciones y configurar la apertura total de petitorios para el mundo exterior.

* **Consistencia Nivel Base de Datos:** `database/migrations/` (Llaves foráneas garantizan cruce de entidades sin generar registros huérfanos).
* **Consistencia Nivel HTTP / Validaciones:** En todas las recepciones (ej. `OrganicoApiController.php`), el estricto `$request->validate([...])` garantiza los tipos y existencia de datos en formato numérico o texto antes de contaminar la base de datos local.
* **Apertura de Consumos Globales (CORS abierto y Exclusión CSRF):** En `bootstrap/app.php`, al establecer `$middleware->validateCsrfTokens(except: ['api/*']);`, se anulan las restricciones que causan error tipo *419* y permite así, aceptar consultas POST/PUT desde un desarrollo Frontend externo local o archivo nativo `file://` permitiendo trabajar comodamente con Angular, React, Vue o Mobile nativo.,,,,,
asdasdasdasd as
asdaasdasd 
as das