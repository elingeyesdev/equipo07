--
-- PostgreSQL database dump
--

\restrict d8tBouH6Bpzh0IhmJhpvmeLqUCMkbJFabsy5sQdJ6IFaf8gJtoemk6HBFqntjRc

-- Dumped from database version 16.13 (Debian 16.13-1.pgdg13+1)
-- Dumped by pg_dump version 16.13 (Debian 16.13-1.pgdg13+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.users DROP CONSTRAINT IF EXISTS users_role_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ubicacion_organico DROP CONSTRAINT IF EXISTS ubicacion_organico_ubicacion_geografica_organico_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ubicacion_maquinaria DROP CONSTRAINT IF EXISTS ubicacion_maquinaria_ubicacion_geografica_maquinaria_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ubicacion_ganado DROP CONSTRAINT IF EXISTS ubicacion_ganado_ubicacion_geografica_ganado_id_foreign;
ALTER TABLE IF EXISTS ONLY public.tratamientos_medicamentos DROP CONSTRAINT IF EXISTS tratamientos_medicamentos_dato_sanitario_id_foreign;
ALTER TABLE IF EXISTS ONLY public.solicitudes_vendedor DROP CONSTRAINT IF EXISTS solicitudes_vendedor_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.reproduccion_logros DROP CONSTRAINT IF EXISTS reproduccion_logros_logro_reconocimiento_id_foreign;
ALTER TABLE IF EXISTS ONLY public.razas DROP CONSTRAINT IF EXISTS razas_tipo_animal_id_foreign;
ALTER TABLE IF EXISTS ONLY public.produccion_leches DROP CONSTRAINT IF EXISTS produccion_leches_logro_reconocimiento_id_foreign;
ALTER TABLE IF EXISTS ONLY public.produccion_carnes DROP CONSTRAINT IF EXISTS produccion_carnes_logro_reconocimiento_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pedidos DROP CONSTRAINT IF EXISTS pedidos_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pedido_detalles DROP CONSTRAINT IF EXISTS pedido_detalles_pedido_id_foreign;
ALTER TABLE IF EXISTS ONLY public.organicos DROP CONSTRAINT IF EXISTS organicos_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.organicos DROP CONSTRAINT IF EXISTS organicos_ubicacion_organico_id_foreign;
ALTER TABLE IF EXISTS ONLY public.organicos DROP CONSTRAINT IF EXISTS organicos_tipo_cultivo_id_foreign;
ALTER TABLE IF EXISTS ONLY public.organicos DROP CONSTRAINT IF EXISTS organicos_categoria_id_foreign;
ALTER TABLE IF EXISTS ONLY public.organico_imagenes DROP CONSTRAINT IF EXISTS organico_imagenes_organico_id_foreign;
ALTER TABLE IF EXISTS ONLY public.marcas_animales DROP CONSTRAINT IF EXISTS marcas_animales_dato_sanitario_id_foreign;
ALTER TABLE IF EXISTS ONLY public.maquinarias DROP CONSTRAINT IF EXISTS maquinarias_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.maquinarias DROP CONSTRAINT IF EXISTS maquinarias_ubicacion_maquinaria_id_foreign;
ALTER TABLE IF EXISTS ONLY public.maquinarias DROP CONSTRAINT IF EXISTS maquinarias_tipo_maquinaria_id_foreign;
ALTER TABLE IF EXISTS ONLY public.maquinarias DROP CONSTRAINT IF EXISTS maquinarias_marca_maquinaria_id_foreign;
ALTER TABLE IF EXISTS ONLY public.maquinarias DROP CONSTRAINT IF EXISTS maquinarias_estado_maquinaria_id_foreign;
ALTER TABLE IF EXISTS ONLY public.maquinarias DROP CONSTRAINT IF EXISTS maquinarias_categoria_id_foreign;
ALTER TABLE IF EXISTS ONLY public.maquinaria_imagenes DROP CONSTRAINT IF EXISTS maquinaria_imagenes_maquinaria_id_foreign;
ALTER TABLE IF EXISTS ONLY public.logros_reconocimientos DROP CONSTRAINT IF EXISTS logros_reconocimientos_dato_sanitario_id_foreign;
ALTER TABLE IF EXISTS ONLY public.imagenes_marca_ganado DROP CONSTRAINT IF EXISTS imagenes_marca_ganado_marca_animal_id_foreign;
ALTER TABLE IF EXISTS ONLY public.imagenes_dato_sanitario_vacunaciones DROP CONSTRAINT IF EXISTS imagenes_dato_sanitario_vacunaciones_dato_sanitario_vacunacion_;
ALTER TABLE IF EXISTS ONLY public.imagenes_certificado_campeon DROP CONSTRAINT IF EXISTS imagenes_certificado_campeon_dato_sanitario_id_foreign;
ALTER TABLE IF EXISTS ONLY public.genealogias_ganado DROP CONSTRAINT IF EXISTS genealogias_ganado_padre_id_foreign;
ALTER TABLE IF EXISTS ONLY public.genealogias_ganado DROP CONSTRAINT IF EXISTS genealogias_ganado_madre_id_foreign;
ALTER TABLE IF EXISTS ONLY public.genealogias_ganado DROP CONSTRAINT IF EXISTS genealogias_ganado_ganado_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ganados DROP CONSTRAINT IF EXISTS ganados_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ganados DROP CONSTRAINT IF EXISTS ganados_ubicacion_ganado_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ganados DROP CONSTRAINT IF EXISTS ganados_tipo_animal_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ganados DROP CONSTRAINT IF EXISTS ganados_raza_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ganados DROP CONSTRAINT IF EXISTS ganados_dato_sanitario_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ganados DROP CONSTRAINT IF EXISTS ganados_categoria_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ganado_imagenes DROP CONSTRAINT IF EXISTS ganado_imagenes_ganado_id_foreign;
ALTER TABLE IF EXISTS ONLY public.datos_sanitarios DROP CONSTRAINT IF EXISTS datos_sanitarios_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.datos_sanitarios DROP CONSTRAINT IF EXISTS datos_sanitarios_ganado_id_foreign;
ALTER TABLE IF EXISTS ONLY public.datos_productivos_ganado DROP CONSTRAINT IF EXISTS datos_productivos_ganado_tipo_peso_id_foreign;
ALTER TABLE IF EXISTS ONLY public.datos_productivos_ganado DROP CONSTRAINT IF EXISTS datos_productivos_ganado_ganado_id_foreign;
ALTER TABLE IF EXISTS ONLY public.datos_duenos DROP CONSTRAINT IF EXISTS datos_duenos_dato_sanitario_id_foreign;
ALTER TABLE IF EXISTS ONLY public.datos_comerciales_organicos DROP CONSTRAINT IF EXISTS datos_comerciales_organicos_unidad_id_foreign;
ALTER TABLE IF EXISTS ONLY public.datos_comerciales_organicos DROP CONSTRAINT IF EXISTS datos_comerciales_organicos_organico_id_foreign;
ALTER TABLE IF EXISTS ONLY public.datos_comerciales_ganado DROP CONSTRAINT IF EXISTS datos_comerciales_ganado_ganado_id_foreign;
ALTER TABLE IF EXISTS ONLY public.dato_sanitario_vacunaciones DROP CONSTRAINT IF EXISTS dato_sanitario_vacunaciones_dato_sanitario_id_foreign;
ALTER TABLE IF EXISTS ONLY public.cart_items DROP CONSTRAINT IF EXISTS cart_items_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.caracteristicas_ganado DROP CONSTRAINT IF EXISTS caracteristicas_ganado_ganado_id_foreign;
ALTER TABLE IF EXISTS ONLY public.belleza_estructuras DROP CONSTRAINT IF EXISTS belleza_estructuras_logro_reconocimiento_id_foreign;
ALTER TABLE IF EXISTS ONLY public.archivos_arbol_genealogico DROP CONSTRAINT IF EXISTS archivos_arbol_genealogico_dato_sanitario_id_foreign;
DROP INDEX IF EXISTS public.solicitudes_vendedor_user_id_estado_index;
DROP INDEX IF EXISTS public.sessions_user_id_index;
DROP INDEX IF EXISTS public.sessions_last_activity_index;
DROP INDEX IF EXISTS public.jobs_queue_index;
ALTER TABLE IF EXISTS ONLY public.users DROP CONSTRAINT IF EXISTS users_pkey;
ALTER TABLE IF EXISTS ONLY public.users DROP CONSTRAINT IF EXISTS users_email_unique;
ALTER TABLE IF EXISTS ONLY public.unidades_organicos DROP CONSTRAINT IF EXISTS unidades_organicos_pkey;
ALTER TABLE IF EXISTS ONLY public.unidades_organicos DROP CONSTRAINT IF EXISTS unidades_organicos_nombre_unique;
ALTER TABLE IF EXISTS ONLY public.ubicacion_organico DROP CONSTRAINT IF EXISTS ubicacion_organico_pkey;
ALTER TABLE IF EXISTS ONLY public.ubicacion_maquinaria DROP CONSTRAINT IF EXISTS ubicacion_maquinaria_pkey;
ALTER TABLE IF EXISTS ONLY public.ubicacion_geografica_organicos DROP CONSTRAINT IF EXISTS ubicacion_geografica_organicos_pkey;
ALTER TABLE IF EXISTS ONLY public.ubicacion_geografica_maquinarias DROP CONSTRAINT IF EXISTS ubicacion_geografica_maquinarias_pkey;
ALTER TABLE IF EXISTS ONLY public.ubicacion_geografica_ganados DROP CONSTRAINT IF EXISTS ubicacion_geografica_ganados_pkey;
ALTER TABLE IF EXISTS ONLY public.ubicacion_geografica_organicos DROP CONSTRAINT IF EXISTS ubicacion_geo_org_unique;
ALTER TABLE IF EXISTS ONLY public.ubicacion_geografica_maquinarias DROP CONSTRAINT IF EXISTS ubicacion_geo_maq_unique;
ALTER TABLE IF EXISTS ONLY public.ubicacion_geografica_ganados DROP CONSTRAINT IF EXISTS ubicacion_geo_ganado_unique;
ALTER TABLE IF EXISTS ONLY public.ubicacion_ganado DROP CONSTRAINT IF EXISTS ubicacion_ganado_pkey;
ALTER TABLE IF EXISTS ONLY public.tratamientos_medicamentos DROP CONSTRAINT IF EXISTS tratamientos_medicamentos_pkey;
ALTER TABLE IF EXISTS ONLY public.tratamientos_medicamentos DROP CONSTRAINT IF EXISTS tratamientos_medicamentos_dato_sanitario_id_unique;
ALTER TABLE IF EXISTS ONLY public.tipo_pesos DROP CONSTRAINT IF EXISTS tipo_pesos_pkey;
ALTER TABLE IF EXISTS ONLY public.tipo_maquinarias DROP CONSTRAINT IF EXISTS tipo_maquinarias_pkey;
ALTER TABLE IF EXISTS ONLY public.tipo_maquinarias DROP CONSTRAINT IF EXISTS tipo_maquinarias_nombre_unique;
ALTER TABLE IF EXISTS ONLY public.tipo_cultivos DROP CONSTRAINT IF EXISTS tipo_cultivos_pkey;
ALTER TABLE IF EXISTS ONLY public.tipo_animals DROP CONSTRAINT IF EXISTS tipo_animals_pkey;
ALTER TABLE IF EXISTS ONLY public.tipo_animals DROP CONSTRAINT IF EXISTS tipo_animals_nombre_unique;
ALTER TABLE IF EXISTS ONLY public.solicitudes_vendedor DROP CONSTRAINT IF EXISTS solicitudes_vendedor_pkey;
ALTER TABLE IF EXISTS ONLY public.sessions DROP CONSTRAINT IF EXISTS sessions_pkey;
ALTER TABLE IF EXISTS ONLY public.roles DROP CONSTRAINT IF EXISTS roles_pkey;
ALTER TABLE IF EXISTS ONLY public.roles DROP CONSTRAINT IF EXISTS roles_nombre_unique;
ALTER TABLE IF EXISTS ONLY public.reproduccion_logros DROP CONSTRAINT IF EXISTS reproduccion_logros_pkey;
ALTER TABLE IF EXISTS ONLY public.reproduccion_logros DROP CONSTRAINT IF EXISTS reproduccion_logros_logro_reconocimiento_id_unique;
ALTER TABLE IF EXISTS ONLY public.razas DROP CONSTRAINT IF EXISTS razas_pkey;
ALTER TABLE IF EXISTS ONLY public.razas DROP CONSTRAINT IF EXISTS razas_nombre_unique;
ALTER TABLE IF EXISTS ONLY public.produccion_leches DROP CONSTRAINT IF EXISTS produccion_leches_pkey;
ALTER TABLE IF EXISTS ONLY public.produccion_leches DROP CONSTRAINT IF EXISTS produccion_leches_logro_reconocimiento_id_unique;
ALTER TABLE IF EXISTS ONLY public.produccion_carnes DROP CONSTRAINT IF EXISTS produccion_carnes_pkey;
ALTER TABLE IF EXISTS ONLY public.produccion_carnes DROP CONSTRAINT IF EXISTS produccion_carnes_logro_reconocimiento_id_unique;
ALTER TABLE IF EXISTS ONLY public.pedidos DROP CONSTRAINT IF EXISTS pedidos_pkey;
ALTER TABLE IF EXISTS ONLY public.pedido_detalles DROP CONSTRAINT IF EXISTS pedido_detalles_pkey;
ALTER TABLE IF EXISTS ONLY public.password_reset_tokens DROP CONSTRAINT IF EXISTS password_reset_tokens_pkey;
ALTER TABLE IF EXISTS ONLY public.organicos DROP CONSTRAINT IF EXISTS organicos_pkey;
ALTER TABLE IF EXISTS ONLY public.organicos DROP CONSTRAINT IF EXISTS organicos_nombre_unique;
ALTER TABLE IF EXISTS ONLY public.organico_imagenes DROP CONSTRAINT IF EXISTS organico_imagenes_pkey;
ALTER TABLE IF EXISTS ONLY public.migrations DROP CONSTRAINT IF EXISTS migrations_pkey;
ALTER TABLE IF EXISTS ONLY public.marcas_maquinarias DROP CONSTRAINT IF EXISTS marcas_maquinarias_pkey;
ALTER TABLE IF EXISTS ONLY public.marcas_maquinarias DROP CONSTRAINT IF EXISTS marcas_maquinarias_nombre_unique;
ALTER TABLE IF EXISTS ONLY public.marcas_animales DROP CONSTRAINT IF EXISTS marcas_animales_pkey;
ALTER TABLE IF EXISTS ONLY public.marcas_animales DROP CONSTRAINT IF EXISTS marcas_animales_dato_sanitario_id_unique;
ALTER TABLE IF EXISTS ONLY public.maquinarias DROP CONSTRAINT IF EXISTS maquinarias_pkey;
ALTER TABLE IF EXISTS ONLY public.maquinaria_imagenes DROP CONSTRAINT IF EXISTS maquinaria_imagenes_pkey;
ALTER TABLE IF EXISTS ONLY public.logros_reconocimientos DROP CONSTRAINT IF EXISTS logros_reconocimientos_pkey;
ALTER TABLE IF EXISTS ONLY public.logros_reconocimientos DROP CONSTRAINT IF EXISTS logros_reconocimientos_dato_sanitario_id_unique;
ALTER TABLE IF EXISTS ONLY public.jobs DROP CONSTRAINT IF EXISTS jobs_pkey;
ALTER TABLE IF EXISTS ONLY public.job_batches DROP CONSTRAINT IF EXISTS job_batches_pkey;
ALTER TABLE IF EXISTS ONLY public.imagenes_marca_ganado DROP CONSTRAINT IF EXISTS imagenes_marca_ganado_pkey;
ALTER TABLE IF EXISTS ONLY public.imagenes_dato_sanitario_vacunaciones DROP CONSTRAINT IF EXISTS imagenes_dato_sanitario_vacunaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.imagenes_certificado_campeon DROP CONSTRAINT IF EXISTS imagenes_certificado_campeon_pkey;
ALTER TABLE IF EXISTS ONLY public.guia_movimientos DROP CONSTRAINT IF EXISTS guia_movimientos_pkey;
ALTER TABLE IF EXISTS ONLY public.genealogias_ganado DROP CONSTRAINT IF EXISTS genealogias_ganado_pkey;
ALTER TABLE IF EXISTS ONLY public.genealogias_ganado DROP CONSTRAINT IF EXISTS genealogias_ganado_ganado_id_unique;
ALTER TABLE IF EXISTS ONLY public.ganados DROP CONSTRAINT IF EXISTS ganados_pkey;
ALTER TABLE IF EXISTS ONLY public.ganado_imagenes DROP CONSTRAINT IF EXISTS ganado_imagenes_pkey;
ALTER TABLE IF EXISTS ONLY public.failed_jobs DROP CONSTRAINT IF EXISTS failed_jobs_uuid_unique;
ALTER TABLE IF EXISTS ONLY public.failed_jobs DROP CONSTRAINT IF EXISTS failed_jobs_pkey;
ALTER TABLE IF EXISTS ONLY public.estado_maquinarias DROP CONSTRAINT IF EXISTS estado_maquinarias_pkey;
ALTER TABLE IF EXISTS ONLY public.estado_maquinarias DROP CONSTRAINT IF EXISTS estado_maquinarias_nombre_unique;
ALTER TABLE IF EXISTS ONLY public.datos_sanitarios DROP CONSTRAINT IF EXISTS datos_sanitarios_pkey;
ALTER TABLE IF EXISTS ONLY public.datos_productivos_ganado DROP CONSTRAINT IF EXISTS datos_productivos_ganado_pkey;
ALTER TABLE IF EXISTS ONLY public.datos_productivos_ganado DROP CONSTRAINT IF EXISTS datos_productivos_ganado_ganado_id_unique;
ALTER TABLE IF EXISTS ONLY public.datos_duenos DROP CONSTRAINT IF EXISTS datos_duenos_pkey;
ALTER TABLE IF EXISTS ONLY public.datos_duenos DROP CONSTRAINT IF EXISTS datos_duenos_dato_sanitario_id_unique;
ALTER TABLE IF EXISTS ONLY public.datos_comerciales_organicos DROP CONSTRAINT IF EXISTS datos_comerciales_organicos_pkey;
ALTER TABLE IF EXISTS ONLY public.datos_comerciales_organicos DROP CONSTRAINT IF EXISTS datos_comerciales_organicos_organico_id_unique;
ALTER TABLE IF EXISTS ONLY public.datos_comerciales_ganado DROP CONSTRAINT IF EXISTS datos_comerciales_ganado_pkey;
ALTER TABLE IF EXISTS ONLY public.datos_comerciales_ganado DROP CONSTRAINT IF EXISTS datos_comerciales_ganado_ganado_id_unique;
ALTER TABLE IF EXISTS ONLY public.dato_sanitario_vacunaciones DROP CONSTRAINT IF EXISTS dato_sanitario_vacunaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.dato_sanitario_vacunaciones DROP CONSTRAINT IF EXISTS dato_sanitario_vacunaciones_dato_sanitario_id_unique;
ALTER TABLE IF EXISTS ONLY public.categorias DROP CONSTRAINT IF EXISTS categorias_pkey;
ALTER TABLE IF EXISTS ONLY public.cart_items DROP CONSTRAINT IF EXISTS cart_items_user_id_product_type_product_id_unique;
ALTER TABLE IF EXISTS ONLY public.cart_items DROP CONSTRAINT IF EXISTS cart_items_pkey;
ALTER TABLE IF EXISTS ONLY public.caracteristicas_ganado DROP CONSTRAINT IF EXISTS caracteristicas_ganado_pkey;
ALTER TABLE IF EXISTS ONLY public.caracteristicas_ganado DROP CONSTRAINT IF EXISTS caracteristicas_ganado_ganado_id_unique;
ALTER TABLE IF EXISTS ONLY public.cache DROP CONSTRAINT IF EXISTS cache_pkey;
ALTER TABLE IF EXISTS ONLY public.cache_locks DROP CONSTRAINT IF EXISTS cache_locks_pkey;
ALTER TABLE IF EXISTS ONLY public.belleza_estructuras DROP CONSTRAINT IF EXISTS belleza_estructuras_pkey;
ALTER TABLE IF EXISTS ONLY public.belleza_estructuras DROP CONSTRAINT IF EXISTS belleza_estructuras_logro_reconocimiento_id_unique;
ALTER TABLE IF EXISTS ONLY public.archivos_arbol_genealogico DROP CONSTRAINT IF EXISTS archivos_arbol_genealogico_pkey;
ALTER TABLE IF EXISTS public.users ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.unidades_organicos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ubicacion_organico ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ubicacion_maquinaria ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ubicacion_geografica_organicos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ubicacion_geografica_maquinarias ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ubicacion_geografica_ganados ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ubicacion_ganado ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tratamientos_medicamentos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tipo_pesos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tipo_maquinarias ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tipo_cultivos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tipo_animals ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.solicitudes_vendedor ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.roles ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.reproduccion_logros ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.razas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.produccion_leches ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.produccion_carnes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.pedidos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.pedido_detalles ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.organicos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.organico_imagenes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.migrations ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.marcas_maquinarias ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.marcas_animales ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.maquinarias ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.maquinaria_imagenes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.logros_reconocimientos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.jobs ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.imagenes_marca_ganado ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.imagenes_dato_sanitario_vacunaciones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.imagenes_certificado_campeon ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.guia_movimientos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.genealogias_ganado ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ganados ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ganado_imagenes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.failed_jobs ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.estado_maquinarias ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.datos_sanitarios ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.datos_productivos_ganado ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.datos_duenos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.datos_comerciales_organicos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.datos_comerciales_ganado ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.dato_sanitario_vacunaciones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.categorias ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.cart_items ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.caracteristicas_ganado ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.belleza_estructuras ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.archivos_arbol_genealogico ALTER COLUMN id DROP DEFAULT;
DROP SEQUENCE IF EXISTS public.users_id_seq;
DROP TABLE IF EXISTS public.users;
DROP SEQUENCE IF EXISTS public.unidades_organicos_id_seq;
DROP TABLE IF EXISTS public.unidades_organicos;
DROP SEQUENCE IF EXISTS public.ubicacion_organico_id_seq;
DROP TABLE IF EXISTS public.ubicacion_organico;
DROP SEQUENCE IF EXISTS public.ubicacion_maquinaria_id_seq;
DROP TABLE IF EXISTS public.ubicacion_maquinaria;
DROP SEQUENCE IF EXISTS public.ubicacion_geografica_organicos_id_seq;
DROP TABLE IF EXISTS public.ubicacion_geografica_organicos;
DROP SEQUENCE IF EXISTS public.ubicacion_geografica_maquinarias_id_seq;
DROP TABLE IF EXISTS public.ubicacion_geografica_maquinarias;
DROP SEQUENCE IF EXISTS public.ubicacion_geografica_ganados_id_seq;
DROP TABLE IF EXISTS public.ubicacion_geografica_ganados;
DROP SEQUENCE IF EXISTS public.ubicacion_ganado_id_seq;
DROP TABLE IF EXISTS public.ubicacion_ganado;
DROP SEQUENCE IF EXISTS public.tratamientos_medicamentos_id_seq;
DROP TABLE IF EXISTS public.tratamientos_medicamentos;
DROP SEQUENCE IF EXISTS public.tipo_pesos_id_seq;
DROP TABLE IF EXISTS public.tipo_pesos;
DROP SEQUENCE IF EXISTS public.tipo_maquinarias_id_seq;
DROP TABLE IF EXISTS public.tipo_maquinarias;
DROP SEQUENCE IF EXISTS public.tipo_cultivos_id_seq;
DROP TABLE IF EXISTS public.tipo_cultivos;
DROP SEQUENCE IF EXISTS public.tipo_animals_id_seq;
DROP TABLE IF EXISTS public.tipo_animals;
DROP SEQUENCE IF EXISTS public.solicitudes_vendedor_id_seq;
DROP TABLE IF EXISTS public.solicitudes_vendedor;
DROP TABLE IF EXISTS public.sessions;
DROP SEQUENCE IF EXISTS public.roles_id_seq;
DROP TABLE IF EXISTS public.roles;
DROP SEQUENCE IF EXISTS public.reproduccion_logros_id_seq;
DROP TABLE IF EXISTS public.reproduccion_logros;
DROP SEQUENCE IF EXISTS public.razas_id_seq;
DROP TABLE IF EXISTS public.razas;
DROP SEQUENCE IF EXISTS public.produccion_leches_id_seq;
DROP TABLE IF EXISTS public.produccion_leches;
DROP SEQUENCE IF EXISTS public.produccion_carnes_id_seq;
DROP TABLE IF EXISTS public.produccion_carnes;
DROP SEQUENCE IF EXISTS public.pedidos_id_seq;
DROP TABLE IF EXISTS public.pedidos;
DROP SEQUENCE IF EXISTS public.pedido_detalles_id_seq;
DROP TABLE IF EXISTS public.pedido_detalles;
DROP TABLE IF EXISTS public.password_reset_tokens;
DROP SEQUENCE IF EXISTS public.organicos_id_seq;
DROP TABLE IF EXISTS public.organicos;
DROP SEQUENCE IF EXISTS public.organico_imagenes_id_seq;
DROP TABLE IF EXISTS public.organico_imagenes;
DROP SEQUENCE IF EXISTS public.migrations_id_seq;
DROP TABLE IF EXISTS public.migrations;
DROP SEQUENCE IF EXISTS public.marcas_maquinarias_id_seq;
DROP TABLE IF EXISTS public.marcas_maquinarias;
DROP SEQUENCE IF EXISTS public.marcas_animales_id_seq;
DROP TABLE IF EXISTS public.marcas_animales;
DROP SEQUENCE IF EXISTS public.maquinarias_id_seq;
DROP TABLE IF EXISTS public.maquinarias;
DROP SEQUENCE IF EXISTS public.maquinaria_imagenes_id_seq;
DROP TABLE IF EXISTS public.maquinaria_imagenes;
DROP SEQUENCE IF EXISTS public.logros_reconocimientos_id_seq;
DROP TABLE IF EXISTS public.logros_reconocimientos;
DROP SEQUENCE IF EXISTS public.jobs_id_seq;
DROP TABLE IF EXISTS public.jobs;
DROP TABLE IF EXISTS public.job_batches;
DROP SEQUENCE IF EXISTS public.imagenes_marca_ganado_id_seq;
DROP TABLE IF EXISTS public.imagenes_marca_ganado;
DROP SEQUENCE IF EXISTS public.imagenes_dato_sanitario_vacunaciones_id_seq;
DROP TABLE IF EXISTS public.imagenes_dato_sanitario_vacunaciones;
DROP SEQUENCE IF EXISTS public.imagenes_certificado_campeon_id_seq;
DROP TABLE IF EXISTS public.imagenes_certificado_campeon;
DROP SEQUENCE IF EXISTS public.guia_movimientos_id_seq;
DROP TABLE IF EXISTS public.guia_movimientos;
DROP SEQUENCE IF EXISTS public.genealogias_ganado_id_seq;
DROP TABLE IF EXISTS public.genealogias_ganado;
DROP SEQUENCE IF EXISTS public.ganados_id_seq;
DROP TABLE IF EXISTS public.ganados;
DROP SEQUENCE IF EXISTS public.ganado_imagenes_id_seq;
DROP TABLE IF EXISTS public.ganado_imagenes;
DROP SEQUENCE IF EXISTS public.failed_jobs_id_seq;
DROP TABLE IF EXISTS public.failed_jobs;
DROP SEQUENCE IF EXISTS public.estado_maquinarias_id_seq;
DROP TABLE IF EXISTS public.estado_maquinarias;
DROP SEQUENCE IF EXISTS public.datos_sanitarios_id_seq;
DROP TABLE IF EXISTS public.datos_sanitarios;
DROP SEQUENCE IF EXISTS public.datos_productivos_ganado_id_seq;
DROP TABLE IF EXISTS public.datos_productivos_ganado;
DROP SEQUENCE IF EXISTS public.datos_duenos_id_seq;
DROP TABLE IF EXISTS public.datos_duenos;
DROP SEQUENCE IF EXISTS public.datos_comerciales_organicos_id_seq;
DROP TABLE IF EXISTS public.datos_comerciales_organicos;
DROP SEQUENCE IF EXISTS public.datos_comerciales_ganado_id_seq;
DROP TABLE IF EXISTS public.datos_comerciales_ganado;
DROP SEQUENCE IF EXISTS public.dato_sanitario_vacunaciones_id_seq;
DROP TABLE IF EXISTS public.dato_sanitario_vacunaciones;
DROP SEQUENCE IF EXISTS public.categorias_id_seq;
DROP TABLE IF EXISTS public.categorias;
DROP SEQUENCE IF EXISTS public.cart_items_id_seq;
DROP TABLE IF EXISTS public.cart_items;
DROP SEQUENCE IF EXISTS public.caracteristicas_ganado_id_seq;
DROP TABLE IF EXISTS public.caracteristicas_ganado;
DROP TABLE IF EXISTS public.cache_locks;
DROP TABLE IF EXISTS public.cache;
DROP SEQUENCE IF EXISTS public.belleza_estructuras_id_seq;
DROP TABLE IF EXISTS public.belleza_estructuras;
DROP SEQUENCE IF EXISTS public.archivos_arbol_genealogico_id_seq;
DROP TABLE IF EXISTS public.archivos_arbol_genealogico;
SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: archivos_arbol_genealogico; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.archivos_arbol_genealogico (
    id bigint NOT NULL,
    dato_sanitario_id bigint NOT NULL,
    ruta character varying(255) NOT NULL,
    orden integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.archivos_arbol_genealogico OWNER TO postgres;

--
-- Name: archivos_arbol_genealogico_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.archivos_arbol_genealogico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.archivos_arbol_genealogico_id_seq OWNER TO postgres;

--
-- Name: archivos_arbol_genealogico_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.archivos_arbol_genealogico_id_seq OWNED BY public.archivos_arbol_genealogico.id;


--
-- Name: belleza_estructuras; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.belleza_estructuras (
    id bigint NOT NULL,
    logro_reconocimiento_id bigint NOT NULL,
    logro_campeon_raza boolean DEFAULT false NOT NULL,
    logro_gran_campeon_macho boolean DEFAULT false NOT NULL,
    logro_gran_campeon_hembra boolean DEFAULT false NOT NULL,
    logro_mejor_ubre boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.belleza_estructuras OWNER TO postgres;

--
-- Name: belleza_estructuras_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.belleza_estructuras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.belleza_estructuras_id_seq OWNER TO postgres;

--
-- Name: belleza_estructuras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.belleza_estructuras_id_seq OWNED BY public.belleza_estructuras.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: caracteristicas_ganado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.caracteristicas_ganado (
    id bigint NOT NULL,
    ganado_id bigint NOT NULL,
    edad integer,
    sexo character varying(255),
    descripcion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.caracteristicas_ganado OWNER TO postgres;

--
-- Name: caracteristicas_ganado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.caracteristicas_ganado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.caracteristicas_ganado_id_seq OWNER TO postgres;

--
-- Name: caracteristicas_ganado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.caracteristicas_ganado_id_seq OWNED BY public.caracteristicas_ganado.id;


--
-- Name: cart_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cart_items (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    product_type character varying(255) NOT NULL,
    product_id bigint NOT NULL,
    cantidad integer DEFAULT 1 NOT NULL,
    precio_unitario numeric(10,2) NOT NULL,
    subtotal numeric(10,2) NOT NULL,
    notas text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.cart_items OWNER TO postgres;

--
-- Name: cart_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cart_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cart_items_id_seq OWNER TO postgres;

--
-- Name: cart_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cart_items_id_seq OWNED BY public.cart_items.id;


--
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorias (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- Name: categorias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categorias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categorias_id_seq OWNER TO postgres;

--
-- Name: categorias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;


--
-- Name: dato_sanitario_vacunaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dato_sanitario_vacunaciones (
    id bigint NOT NULL,
    dato_sanitario_id bigint NOT NULL,
    vacuna character varying(255),
    vacunado_fiebre_aftosa boolean DEFAULT false NOT NULL,
    vacunado_antirabica boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.dato_sanitario_vacunaciones OWNER TO postgres;

--
-- Name: dato_sanitario_vacunaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dato_sanitario_vacunaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.dato_sanitario_vacunaciones_id_seq OWNER TO postgres;

--
-- Name: dato_sanitario_vacunaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dato_sanitario_vacunaciones_id_seq OWNED BY public.dato_sanitario_vacunaciones.id;


--
-- Name: datos_comerciales_ganado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.datos_comerciales_ganado (
    id bigint NOT NULL,
    ganado_id bigint NOT NULL,
    precio numeric(10,2),
    stock integer DEFAULT 0 NOT NULL,
    fecha_publicacion date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.datos_comerciales_ganado OWNER TO postgres;

--
-- Name: datos_comerciales_ganado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.datos_comerciales_ganado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.datos_comerciales_ganado_id_seq OWNER TO postgres;

--
-- Name: datos_comerciales_ganado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.datos_comerciales_ganado_id_seq OWNED BY public.datos_comerciales_ganado.id;


--
-- Name: datos_comerciales_organicos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.datos_comerciales_organicos (
    id bigint NOT NULL,
    organico_id bigint NOT NULL,
    unidad_id bigint,
    precio numeric(10,2),
    stock integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.datos_comerciales_organicos OWNER TO postgres;

--
-- Name: datos_comerciales_organicos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.datos_comerciales_organicos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.datos_comerciales_organicos_id_seq OWNER TO postgres;

--
-- Name: datos_comerciales_organicos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.datos_comerciales_organicos_id_seq OWNED BY public.datos_comerciales_organicos.id;


--
-- Name: datos_duenos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.datos_duenos (
    id bigint NOT NULL,
    dato_sanitario_id bigint NOT NULL,
    nombre_dueno character varying(255),
    carnet_dueno_foto character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.datos_duenos OWNER TO postgres;

--
-- Name: datos_duenos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.datos_duenos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.datos_duenos_id_seq OWNER TO postgres;

--
-- Name: datos_duenos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.datos_duenos_id_seq OWNED BY public.datos_duenos.id;


--
-- Name: datos_productivos_ganado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.datos_productivos_ganado (
    id bigint NOT NULL,
    ganado_id bigint NOT NULL,
    tipo_peso_id bigint,
    peso_actual numeric(10,2),
    cantidad_leche_dia numeric(8,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.datos_productivos_ganado OWNER TO postgres;

--
-- Name: datos_productivos_ganado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.datos_productivos_ganado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.datos_productivos_ganado_id_seq OWNER TO postgres;

--
-- Name: datos_productivos_ganado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.datos_productivos_ganado_id_seq OWNED BY public.datos_productivos_ganado.id;


--
-- Name: datos_sanitarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.datos_sanitarios (
    id bigint NOT NULL,
    ganado_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    destino_matadero_campo character varying(255),
    hoja_ruta_foto character varying(255),
    user_id bigint
);


ALTER TABLE public.datos_sanitarios OWNER TO postgres;

--
-- Name: datos_sanitarios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.datos_sanitarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.datos_sanitarios_id_seq OWNER TO postgres;

--
-- Name: datos_sanitarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.datos_sanitarios_id_seq OWNED BY public.datos_sanitarios.id;


--
-- Name: estado_maquinarias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.estado_maquinarias (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.estado_maquinarias OWNER TO postgres;

--
-- Name: estado_maquinarias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.estado_maquinarias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.estado_maquinarias_id_seq OWNER TO postgres;

--
-- Name: estado_maquinarias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.estado_maquinarias_id_seq OWNED BY public.estado_maquinarias.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: ganado_imagenes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ganado_imagenes (
    id bigint NOT NULL,
    ganado_id bigint NOT NULL,
    ruta character varying(255) NOT NULL,
    orden integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ganado_imagenes OWNER TO postgres;

--
-- Name: ganado_imagenes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ganado_imagenes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ganado_imagenes_id_seq OWNER TO postgres;

--
-- Name: ganado_imagenes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ganado_imagenes_id_seq OWNED BY public.ganado_imagenes.id;


--
-- Name: ganados; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ganados (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    tipo_animal_id bigint NOT NULL,
    raza_id bigint,
    categoria_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id bigint,
    dato_sanitario_id bigint,
    es_campeon boolean DEFAULT false NOT NULL,
    ubicacion_ganado_id bigint
);


ALTER TABLE public.ganados OWNER TO postgres;

--
-- Name: ganados_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ganados_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ganados_id_seq OWNER TO postgres;

--
-- Name: ganados_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ganados_id_seq OWNED BY public.ganados.id;


--
-- Name: genealogias_ganado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.genealogias_ganado (
    id bigint NOT NULL,
    ganado_id bigint NOT NULL,
    madre_id bigint,
    padre_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.genealogias_ganado OWNER TO postgres;

--
-- Name: genealogias_ganado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.genealogias_ganado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.genealogias_ganado_id_seq OWNER TO postgres;

--
-- Name: genealogias_ganado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.genealogias_ganado_id_seq OWNED BY public.genealogias_ganado.id;


--
-- Name: guia_movimientos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.guia_movimientos (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.guia_movimientos OWNER TO postgres;

--
-- Name: guia_movimientos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.guia_movimientos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.guia_movimientos_id_seq OWNER TO postgres;

--
-- Name: guia_movimientos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.guia_movimientos_id_seq OWNED BY public.guia_movimientos.id;


--
-- Name: imagenes_certificado_campeon; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.imagenes_certificado_campeon (
    id bigint NOT NULL,
    dato_sanitario_id bigint NOT NULL,
    ruta character varying(255) NOT NULL,
    orden integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.imagenes_certificado_campeon OWNER TO postgres;

--
-- Name: imagenes_certificado_campeon_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.imagenes_certificado_campeon_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.imagenes_certificado_campeon_id_seq OWNER TO postgres;

--
-- Name: imagenes_certificado_campeon_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.imagenes_certificado_campeon_id_seq OWNED BY public.imagenes_certificado_campeon.id;


--
-- Name: imagenes_dato_sanitario_vacunaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.imagenes_dato_sanitario_vacunaciones (
    id bigint NOT NULL,
    dato_sanitario_vacunacion_id bigint NOT NULL,
    ruta character varying(255) NOT NULL,
    orden integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.imagenes_dato_sanitario_vacunaciones OWNER TO postgres;

--
-- Name: imagenes_dato_sanitario_vacunaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.imagenes_dato_sanitario_vacunaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.imagenes_dato_sanitario_vacunaciones_id_seq OWNER TO postgres;

--
-- Name: imagenes_dato_sanitario_vacunaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.imagenes_dato_sanitario_vacunaciones_id_seq OWNED BY public.imagenes_dato_sanitario_vacunaciones.id;


--
-- Name: imagenes_marca_ganado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.imagenes_marca_ganado (
    id bigint NOT NULL,
    marca_animal_id bigint NOT NULL,
    ruta character varying(255) NOT NULL,
    orden integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.imagenes_marca_ganado OWNER TO postgres;

--
-- Name: imagenes_marca_ganado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.imagenes_marca_ganado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.imagenes_marca_ganado_id_seq OWNER TO postgres;

--
-- Name: imagenes_marca_ganado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.imagenes_marca_ganado_id_seq OWNED BY public.imagenes_marca_ganado.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: logros_reconocimientos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.logros_reconocimientos (
    id bigint NOT NULL,
    dato_sanitario_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.logros_reconocimientos OWNER TO postgres;

--
-- Name: logros_reconocimientos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.logros_reconocimientos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.logros_reconocimientos_id_seq OWNER TO postgres;

--
-- Name: logros_reconocimientos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.logros_reconocimientos_id_seq OWNED BY public.logros_reconocimientos.id;


--
-- Name: maquinaria_imagenes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.maquinaria_imagenes (
    id bigint NOT NULL,
    maquinaria_id bigint NOT NULL,
    ruta character varying(255) NOT NULL,
    orden integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.maquinaria_imagenes OWNER TO postgres;

--
-- Name: maquinaria_imagenes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.maquinaria_imagenes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.maquinaria_imagenes_id_seq OWNER TO postgres;

--
-- Name: maquinaria_imagenes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.maquinaria_imagenes_id_seq OWNED BY public.maquinaria_imagenes.id;


--
-- Name: maquinarias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.maquinarias (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    modelo character varying(255),
    precio_dia numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    descripcion text,
    categoria_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id bigint,
    tipo_maquinaria_id bigint,
    marca_maquinaria_id bigint,
    telefono character varying(255),
    estado_maquinaria_id bigint,
    ubicacion_maquinaria_id bigint
);


ALTER TABLE public.maquinarias OWNER TO postgres;

--
-- Name: maquinarias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.maquinarias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.maquinarias_id_seq OWNER TO postgres;

--
-- Name: maquinarias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.maquinarias_id_seq OWNED BY public.maquinarias.id;


--
-- Name: marcas_animales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.marcas_animales (
    id bigint NOT NULL,
    dato_sanitario_id bigint NOT NULL,
    marca_ganado character varying(255),
    senal_numero character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.marcas_animales OWNER TO postgres;

--
-- Name: marcas_animales_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.marcas_animales_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.marcas_animales_id_seq OWNER TO postgres;

--
-- Name: marcas_animales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.marcas_animales_id_seq OWNED BY public.marcas_animales.id;


--
-- Name: marcas_maquinarias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.marcas_maquinarias (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.marcas_maquinarias OWNER TO postgres;

--
-- Name: marcas_maquinarias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.marcas_maquinarias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.marcas_maquinarias_id_seq OWNER TO postgres;

--
-- Name: marcas_maquinarias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.marcas_maquinarias_id_seq OWNED BY public.marcas_maquinarias.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: organico_imagenes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.organico_imagenes (
    id bigint NOT NULL,
    organico_id bigint NOT NULL,
    ruta character varying(255) NOT NULL,
    orden integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.organico_imagenes OWNER TO postgres;

--
-- Name: organico_imagenes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.organico_imagenes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.organico_imagenes_id_seq OWNER TO postgres;

--
-- Name: organico_imagenes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.organico_imagenes_id_seq OWNED BY public.organico_imagenes.id;


--
-- Name: organicos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.organicos (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    categoria_id bigint NOT NULL,
    fecha_cosecha date,
    descripcion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id bigint,
    tipo_cultivo_id bigint,
    ubicacion_organico_id bigint
);


ALTER TABLE public.organicos OWNER TO postgres;

--
-- Name: organicos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.organicos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.organicos_id_seq OWNER TO postgres;

--
-- Name: organicos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.organicos_id_seq OWNED BY public.organicos.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: pedido_detalles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pedido_detalles (
    id bigint NOT NULL,
    pedido_id bigint NOT NULL,
    product_id bigint NOT NULL,
    product_type character varying(255) NOT NULL,
    nombre_producto character varying(255) NOT NULL,
    cantidad integer NOT NULL,
    precio_unitario numeric(10,2) NOT NULL,
    subtotal numeric(10,2) NOT NULL,
    notas character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.pedido_detalles OWNER TO postgres;

--
-- Name: pedido_detalles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pedido_detalles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pedido_detalles_id_seq OWNER TO postgres;

--
-- Name: pedido_detalles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pedido_detalles_id_seq OWNED BY public.pedido_detalles.id;


--
-- Name: pedidos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pedidos (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    total numeric(10,2) NOT NULL,
    estado character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    metodo_pago character varying(255),
    observaciones text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.pedidos OWNER TO postgres;

--
-- Name: pedidos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pedidos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pedidos_id_seq OWNER TO postgres;

--
-- Name: pedidos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pedidos_id_seq OWNED BY public.pedidos.id;


--
-- Name: produccion_carnes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.produccion_carnes (
    id bigint NOT NULL,
    logro_reconocimiento_id bigint NOT NULL,
    logro_mejor_novillo boolean DEFAULT false NOT NULL,
    logro_gran_campeon_carne boolean DEFAULT false NOT NULL,
    logro_mejor_semental boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.produccion_carnes OWNER TO postgres;

--
-- Name: produccion_carnes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.produccion_carnes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.produccion_carnes_id_seq OWNER TO postgres;

--
-- Name: produccion_carnes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.produccion_carnes_id_seq OWNED BY public.produccion_carnes.id;


--
-- Name: produccion_leches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.produccion_leches (
    id bigint NOT NULL,
    logro_reconocimiento_id bigint NOT NULL,
    logro_campeona_litros_dia boolean DEFAULT false NOT NULL,
    logro_mejor_lactancia boolean DEFAULT false NOT NULL,
    logro_mejor_calidad_leche boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.produccion_leches OWNER TO postgres;

--
-- Name: produccion_leches_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.produccion_leches_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.produccion_leches_id_seq OWNER TO postgres;

--
-- Name: produccion_leches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.produccion_leches_id_seq OWNED BY public.produccion_leches.id;


--
-- Name: razas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.razas (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text,
    tipo_animal_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.razas OWNER TO postgres;

--
-- Name: razas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.razas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.razas_id_seq OWNER TO postgres;

--
-- Name: razas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.razas_id_seq OWNED BY public.razas.id;


--
-- Name: reproduccion_logros; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reproduccion_logros (
    id bigint NOT NULL,
    logro_reconocimiento_id bigint NOT NULL,
    logro_mejor_madre boolean DEFAULT false NOT NULL,
    logro_mejor_padre boolean DEFAULT false NOT NULL,
    logro_mejor_fertilidad boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.reproduccion_logros OWNER TO postgres;

--
-- Name: reproduccion_logros_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reproduccion_logros_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reproduccion_logros_id_seq OWNER TO postgres;

--
-- Name: reproduccion_logros_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reproduccion_logros_id_seq OWNED BY public.reproduccion_logros.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: solicitudes_vendedor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.solicitudes_vendedor (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    motivo text NOT NULL,
    telefono character varying(20) NOT NULL,
    direccion character varying(255) NOT NULL,
    documento character varying(255),
    archivo_documento character varying(255),
    estado character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    fecha_revision_admin timestamp(0) without time zone,
    CONSTRAINT solicitudes_vendedor_estado_check CHECK (((estado)::text = ANY ((ARRAY['pendiente'::character varying, 'aprobada'::character varying, 'rechazada'::character varying])::text[])))
);


ALTER TABLE public.solicitudes_vendedor OWNER TO postgres;

--
-- Name: solicitudes_vendedor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.solicitudes_vendedor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.solicitudes_vendedor_id_seq OWNER TO postgres;

--
-- Name: solicitudes_vendedor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.solicitudes_vendedor_id_seq OWNED BY public.solicitudes_vendedor.id;


--
-- Name: tipo_animals; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_animals (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.tipo_animals OWNER TO postgres;

--
-- Name: tipo_animals_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_animals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tipo_animals_id_seq OWNER TO postgres;

--
-- Name: tipo_animals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_animals_id_seq OWNED BY public.tipo_animals.id;


--
-- Name: tipo_cultivos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_cultivos (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.tipo_cultivos OWNER TO postgres;

--
-- Name: tipo_cultivos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_cultivos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tipo_cultivos_id_seq OWNER TO postgres;

--
-- Name: tipo_cultivos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_cultivos_id_seq OWNED BY public.tipo_cultivos.id;


--
-- Name: tipo_maquinarias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_maquinarias (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.tipo_maquinarias OWNER TO postgres;

--
-- Name: tipo_maquinarias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_maquinarias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tipo_maquinarias_id_seq OWNER TO postgres;

--
-- Name: tipo_maquinarias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_maquinarias_id_seq OWNED BY public.tipo_maquinarias.id;


--
-- Name: tipo_pesos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_pesos (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.tipo_pesos OWNER TO postgres;

--
-- Name: tipo_pesos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_pesos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tipo_pesos_id_seq OWNER TO postgres;

--
-- Name: tipo_pesos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_pesos_id_seq OWNED BY public.tipo_pesos.id;


--
-- Name: tratamientos_medicamentos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tratamientos_medicamentos (
    id bigint NOT NULL,
    dato_sanitario_id bigint NOT NULL,
    tratamiento character varying(255),
    medicamento character varying(255),
    fecha_aplicacion date,
    proxima_fecha date,
    veterinario character varying(255),
    observaciones text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.tratamientos_medicamentos OWNER TO postgres;

--
-- Name: tratamientos_medicamentos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tratamientos_medicamentos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tratamientos_medicamentos_id_seq OWNER TO postgres;

--
-- Name: tratamientos_medicamentos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tratamientos_medicamentos_id_seq OWNED BY public.tratamientos_medicamentos.id;


--
-- Name: ubicacion_ganado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ubicacion_ganado (
    id bigint NOT NULL,
    ubicacion character varying(255),
    latitud numeric(10,7),
    longitud numeric(10,7),
    ubicacion_geografica_ganado_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ubicacion_ganado OWNER TO postgres;

--
-- Name: ubicacion_ganado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ubicacion_ganado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ubicacion_ganado_id_seq OWNER TO postgres;

--
-- Name: ubicacion_ganado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ubicacion_ganado_id_seq OWNED BY public.ubicacion_ganado.id;


--
-- Name: ubicacion_geografica_ganados; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ubicacion_geografica_ganados (
    id bigint NOT NULL,
    departamento character varying(255),
    municipio character varying(255),
    provincia character varying(255),
    ciudad character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ubicacion_geografica_ganados OWNER TO postgres;

--
-- Name: ubicacion_geografica_ganados_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ubicacion_geografica_ganados_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ubicacion_geografica_ganados_id_seq OWNER TO postgres;

--
-- Name: ubicacion_geografica_ganados_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ubicacion_geografica_ganados_id_seq OWNED BY public.ubicacion_geografica_ganados.id;


--
-- Name: ubicacion_geografica_maquinarias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ubicacion_geografica_maquinarias (
    id bigint NOT NULL,
    departamento character varying(255),
    municipio character varying(255),
    provincia character varying(255),
    ciudad character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ubicacion_geografica_maquinarias OWNER TO postgres;

--
-- Name: ubicacion_geografica_maquinarias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ubicacion_geografica_maquinarias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ubicacion_geografica_maquinarias_id_seq OWNER TO postgres;

--
-- Name: ubicacion_geografica_maquinarias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ubicacion_geografica_maquinarias_id_seq OWNED BY public.ubicacion_geografica_maquinarias.id;


--
-- Name: ubicacion_geografica_organicos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ubicacion_geografica_organicos (
    id bigint NOT NULL,
    departamento character varying(255),
    municipio character varying(255),
    provincia character varying(255),
    ciudad character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ubicacion_geografica_organicos OWNER TO postgres;

--
-- Name: ubicacion_geografica_organicos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ubicacion_geografica_organicos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ubicacion_geografica_organicos_id_seq OWNER TO postgres;

--
-- Name: ubicacion_geografica_organicos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ubicacion_geografica_organicos_id_seq OWNED BY public.ubicacion_geografica_organicos.id;


--
-- Name: ubicacion_maquinaria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ubicacion_maquinaria (
    id bigint NOT NULL,
    ubicacion character varying(255),
    latitud numeric(10,7),
    longitud numeric(10,7),
    ubicacion_geografica_maquinaria_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ubicacion_maquinaria OWNER TO postgres;

--
-- Name: ubicacion_maquinaria_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ubicacion_maquinaria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ubicacion_maquinaria_id_seq OWNER TO postgres;

--
-- Name: ubicacion_maquinaria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ubicacion_maquinaria_id_seq OWNED BY public.ubicacion_maquinaria.id;


--
-- Name: ubicacion_organico; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ubicacion_organico (
    id bigint NOT NULL,
    ubicacion character varying(255),
    latitud numeric(10,7),
    longitud numeric(10,7),
    ubicacion_geografica_organico_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ubicacion_organico OWNER TO postgres;

--
-- Name: ubicacion_organico_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ubicacion_organico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ubicacion_organico_id_seq OWNER TO postgres;

--
-- Name: ubicacion_organico_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ubicacion_organico_id_seq OWNED BY public.ubicacion_organico.id;


--
-- Name: unidades_organicos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.unidades_organicos (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.unidades_organicos OWNER TO postgres;

--
-- Name: unidades_organicos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.unidades_organicos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.unidades_organicos_id_seq OWNER TO postgres;

--
-- Name: unidades_organicos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.unidades_organicos_id_seq OWNED BY public.unidades_organicos.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'cliente'::character varying NOT NULL,
    role_id bigint
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: archivos_arbol_genealogico id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archivos_arbol_genealogico ALTER COLUMN id SET DEFAULT nextval('public.archivos_arbol_genealogico_id_seq'::regclass);


--
-- Name: belleza_estructuras id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.belleza_estructuras ALTER COLUMN id SET DEFAULT nextval('public.belleza_estructuras_id_seq'::regclass);


--
-- Name: caracteristicas_ganado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.caracteristicas_ganado ALTER COLUMN id SET DEFAULT nextval('public.caracteristicas_ganado_id_seq'::regclass);


--
-- Name: cart_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cart_items ALTER COLUMN id SET DEFAULT nextval('public.cart_items_id_seq'::regclass);


--
-- Name: categorias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);


--
-- Name: dato_sanitario_vacunaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dato_sanitario_vacunaciones ALTER COLUMN id SET DEFAULT nextval('public.dato_sanitario_vacunaciones_id_seq'::regclass);


--
-- Name: datos_comerciales_ganado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_comerciales_ganado ALTER COLUMN id SET DEFAULT nextval('public.datos_comerciales_ganado_id_seq'::regclass);


--
-- Name: datos_comerciales_organicos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_comerciales_organicos ALTER COLUMN id SET DEFAULT nextval('public.datos_comerciales_organicos_id_seq'::regclass);


--
-- Name: datos_duenos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_duenos ALTER COLUMN id SET DEFAULT nextval('public.datos_duenos_id_seq'::regclass);


--
-- Name: datos_productivos_ganado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_productivos_ganado ALTER COLUMN id SET DEFAULT nextval('public.datos_productivos_ganado_id_seq'::regclass);


--
-- Name: datos_sanitarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_sanitarios ALTER COLUMN id SET DEFAULT nextval('public.datos_sanitarios_id_seq'::regclass);


--
-- Name: estado_maquinarias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.estado_maquinarias ALTER COLUMN id SET DEFAULT nextval('public.estado_maquinarias_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: ganado_imagenes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_imagenes ALTER COLUMN id SET DEFAULT nextval('public.ganado_imagenes_id_seq'::regclass);


--
-- Name: ganados id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados ALTER COLUMN id SET DEFAULT nextval('public.ganados_id_seq'::regclass);


--
-- Name: genealogias_ganado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.genealogias_ganado ALTER COLUMN id SET DEFAULT nextval('public.genealogias_ganado_id_seq'::regclass);


--
-- Name: guia_movimientos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.guia_movimientos ALTER COLUMN id SET DEFAULT nextval('public.guia_movimientos_id_seq'::regclass);


--
-- Name: imagenes_certificado_campeon id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.imagenes_certificado_campeon ALTER COLUMN id SET DEFAULT nextval('public.imagenes_certificado_campeon_id_seq'::regclass);


--
-- Name: imagenes_dato_sanitario_vacunaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.imagenes_dato_sanitario_vacunaciones ALTER COLUMN id SET DEFAULT nextval('public.imagenes_dato_sanitario_vacunaciones_id_seq'::regclass);


--
-- Name: imagenes_marca_ganado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.imagenes_marca_ganado ALTER COLUMN id SET DEFAULT nextval('public.imagenes_marca_ganado_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: logros_reconocimientos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logros_reconocimientos ALTER COLUMN id SET DEFAULT nextval('public.logros_reconocimientos_id_seq'::regclass);


--
-- Name: maquinaria_imagenes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinaria_imagenes ALTER COLUMN id SET DEFAULT nextval('public.maquinaria_imagenes_id_seq'::regclass);


--
-- Name: maquinarias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinarias ALTER COLUMN id SET DEFAULT nextval('public.maquinarias_id_seq'::regclass);


--
-- Name: marcas_animales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marcas_animales ALTER COLUMN id SET DEFAULT nextval('public.marcas_animales_id_seq'::regclass);


--
-- Name: marcas_maquinarias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marcas_maquinarias ALTER COLUMN id SET DEFAULT nextval('public.marcas_maquinarias_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: organico_imagenes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organico_imagenes ALTER COLUMN id SET DEFAULT nextval('public.organico_imagenes_id_seq'::regclass);


--
-- Name: organicos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organicos ALTER COLUMN id SET DEFAULT nextval('public.organicos_id_seq'::regclass);


--
-- Name: pedido_detalles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedido_detalles ALTER COLUMN id SET DEFAULT nextval('public.pedido_detalles_id_seq'::regclass);


--
-- Name: pedidos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos ALTER COLUMN id SET DEFAULT nextval('public.pedidos_id_seq'::regclass);


--
-- Name: produccion_carnes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produccion_carnes ALTER COLUMN id SET DEFAULT nextval('public.produccion_carnes_id_seq'::regclass);


--
-- Name: produccion_leches id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produccion_leches ALTER COLUMN id SET DEFAULT nextval('public.produccion_leches_id_seq'::regclass);


--
-- Name: razas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.razas ALTER COLUMN id SET DEFAULT nextval('public.razas_id_seq'::regclass);


--
-- Name: reproduccion_logros id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reproduccion_logros ALTER COLUMN id SET DEFAULT nextval('public.reproduccion_logros_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: solicitudes_vendedor id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitudes_vendedor ALTER COLUMN id SET DEFAULT nextval('public.solicitudes_vendedor_id_seq'::regclass);


--
-- Name: tipo_animals id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_animals ALTER COLUMN id SET DEFAULT nextval('public.tipo_animals_id_seq'::regclass);


--
-- Name: tipo_cultivos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_cultivos ALTER COLUMN id SET DEFAULT nextval('public.tipo_cultivos_id_seq'::regclass);


--
-- Name: tipo_maquinarias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_maquinarias ALTER COLUMN id SET DEFAULT nextval('public.tipo_maquinarias_id_seq'::regclass);


--
-- Name: tipo_pesos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_pesos ALTER COLUMN id SET DEFAULT nextval('public.tipo_pesos_id_seq'::regclass);


--
-- Name: tratamientos_medicamentos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tratamientos_medicamentos ALTER COLUMN id SET DEFAULT nextval('public.tratamientos_medicamentos_id_seq'::regclass);


--
-- Name: ubicacion_ganado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_ganado ALTER COLUMN id SET DEFAULT nextval('public.ubicacion_ganado_id_seq'::regclass);


--
-- Name: ubicacion_geografica_ganados id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_geografica_ganados ALTER COLUMN id SET DEFAULT nextval('public.ubicacion_geografica_ganados_id_seq'::regclass);


--
-- Name: ubicacion_geografica_maquinarias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_geografica_maquinarias ALTER COLUMN id SET DEFAULT nextval('public.ubicacion_geografica_maquinarias_id_seq'::regclass);


--
-- Name: ubicacion_geografica_organicos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_geografica_organicos ALTER COLUMN id SET DEFAULT nextval('public.ubicacion_geografica_organicos_id_seq'::regclass);


--
-- Name: ubicacion_maquinaria id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_maquinaria ALTER COLUMN id SET DEFAULT nextval('public.ubicacion_maquinaria_id_seq'::regclass);


--
-- Name: ubicacion_organico id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_organico ALTER COLUMN id SET DEFAULT nextval('public.ubicacion_organico_id_seq'::regclass);


--
-- Name: unidades_organicos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.unidades_organicos ALTER COLUMN id SET DEFAULT nextval('public.unidades_organicos_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: archivos_arbol_genealogico; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.archivos_arbol_genealogico (id, dato_sanitario_id, ruta, orden, created_at, updated_at) FROM stdin;
1	1	arboles_genealogicos/wFqXOm8UgMhwwEzJV6dLaD2BqzYCn8iSDAonjp9p.png	0	2026-05-17 10:17:32	2026-05-17 10:17:32
2	2	arboles_genealogicos/ymXj2GG6ebbFOOlOWo0ts76361iUPDzObMav0KmE.png	0	2026-05-17 10:17:32	2026-05-17 10:17:32
\.


--
-- Data for Name: belleza_estructuras; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.belleza_estructuras (id, logro_reconocimiento_id, logro_campeon_raza, logro_gran_campeon_macho, logro_gran_campeon_hembra, logro_mejor_ubre, created_at, updated_at) FROM stdin;
1	1	t	t	f	t	2026-05-17 10:07:09	2026-05-17 10:07:09
2	2	t	t	t	t	2026-05-17 10:07:09	2026-05-17 10:07:09
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: caracteristicas_ganado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.caracteristicas_ganado (id, ganado_id, edad, sexo, descripcion, created_at, updated_at) FROM stdin;
1	1	24	Macho	qsdasd	2026-05-17 10:43:28	2026-05-17 10:43:28
2	2	36	Macho	asdad	2026-05-17 10:56:58	2026-05-17 10:56:58
\.


--
-- Data for Name: cart_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cart_items (id, user_id, product_type, product_id, cantidad, precio_unitario, subtotal, notas, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categorias (id, nombre, descripcion, created_at, updated_at) FROM stdin;
1	asd	123	2026-05-15 03:06:03	2026-05-15 03:06:03
\.


--
-- Data for Name: dato_sanitario_vacunaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dato_sanitario_vacunaciones (id, dato_sanitario_id, vacuna, vacunado_fiebre_aftosa, vacunado_antirabica, created_at, updated_at) FROM stdin;
1	1	adada	t	t	2026-05-17 09:37:20	2026-05-17 09:37:20
2	2	noasdad	t	f	2026-05-17 09:41:18	2026-05-17 09:43:01
\.


--
-- Data for Name: datos_comerciales_ganado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.datos_comerciales_ganado (id, ganado_id, precio, stock, fecha_publicacion, created_at, updated_at) FROM stdin;
1	1	123123.00	11	2026-05-15	2026-05-17 10:43:28	2026-05-17 10:43:28
2	2	1111.00	111	2026-05-17	2026-05-17 10:56:58	2026-05-17 10:56:58
\.


--
-- Data for Name: datos_comerciales_organicos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.datos_comerciales_organicos (id, organico_id, unidad_id, precio, stock, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: datos_duenos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.datos_duenos (id, dato_sanitario_id, nombre_dueno, carnet_dueno_foto, created_at, updated_at) FROM stdin;
1	1	asdada	carnets_dueños/qBKjkwMoMboxAE1fZi6JWeOzJlBitL8mIe3Ox31m.png	2026-05-17 09:37:21	2026-05-17 09:37:21
2	2	farrq	carnets_dueños/INaa4UBaSEFtAoyQ7NeRzgI6EwFJffP6MbsqaCBd.png	2026-05-17 09:41:18	2026-05-17 09:43:25
\.


--
-- Data for Name: datos_productivos_ganado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.datos_productivos_ganado (id, ganado_id, tipo_peso_id, peso_actual, cantidad_leche_dia, created_at, updated_at) FROM stdin;
1	1	1	1231231.00	\N	2026-05-17 10:43:28	2026-05-17 10:43:28
2	2	1	1212.00	\N	2026-05-17 10:56:58	2026-05-17 10:56:58
\.


--
-- Data for Name: datos_sanitarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.datos_sanitarios (id, ganado_id, created_at, updated_at, destino_matadero_campo, hoja_ruta_foto, user_id) FROM stdin;
1	1	2026-05-17 09:24:36	2026-05-17 09:24:36	\N	\N	1
2	1	2026-05-17 09:41:18	2026-05-17 09:43:25	\N	\N	1
\.


--
-- Data for Name: estado_maquinarias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.estado_maquinarias (id, nombre, descripcion, created_at, updated_at) FROM stdin;
1	disponible	Maquinaria disponible para alquiler	2026-05-13 07:16:02	2026-05-13 07:16:02
2	en_mantenimiento	Maquinaria en mantenimiento	2026-05-13 07:16:02	2026-05-13 07:16:02
3	dado_baja	Maquinaria dado de baja	2026-05-13 07:16:02	2026-05-13 07:16:02
4	en_uso	Maquinaria actualmente en uso	2026-05-13 07:16:02	2026-05-13 07:16:02
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: ganado_imagenes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ganado_imagenes (id, ganado_id, ruta, orden, created_at, updated_at) FROM stdin;
1	1	ganados/Ff2YprI8zZD0fThFKVhW4rQYzLgopcpFRatyDr3E.png	0	2026-05-15 08:32:53	2026-05-15 08:32:53
2	2	ganados/C7AlXfORvxwrkdGXIhfbhxiFwf1NVi19tSBXrkhy.png	0	2026-05-17 10:56:58	2026-05-17 10:56:58
\.


--
-- Data for Name: ganados; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ganados (id, nombre, tipo_animal_id, raza_id, categoria_id, created_at, updated_at, user_id, dato_sanitario_id, es_campeon, ubicacion_ganado_id) FROM stdin;
1	asdadasd	1	\N	1	2026-05-15 08:32:53	2026-05-17 09:41:18	1	2	f	1
2	sdfsdfsdfsf	1	1	1	2026-05-17 10:56:58	2026-05-17 10:56:58	1	1	f	2
\.


--
-- Data for Name: genealogias_ganado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.genealogias_ganado (id, ganado_id, madre_id, padre_id, created_at, updated_at) FROM stdin;
1	1	\N	\N	2026-05-17 10:43:28	2026-05-17 10:43:28
2	2	\N	\N	2026-05-17 10:56:58	2026-05-17 10:56:58
\.


--
-- Data for Name: guia_movimientos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.guia_movimientos (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: imagenes_certificado_campeon; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.imagenes_certificado_campeon (id, dato_sanitario_id, ruta, orden, created_at, updated_at) FROM stdin;
1	1	certificados_campeon/qb1kbsRfSEaRKuGz5YXxa2W6bFyI7oVm8i6nD5Jw.jpg	0	2026-05-17 10:17:32	2026-05-17 10:17:32
2	2	certificados_campeon/NAImyvAozEc1fpCMJ4d3Req88tihK2QFJRHqIvAV.png	0	2026-05-17 10:17:32	2026-05-17 10:17:32
\.


--
-- Data for Name: imagenes_dato_sanitario_vacunaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.imagenes_dato_sanitario_vacunaciones (id, dato_sanitario_vacunacion_id, ruta, orden, created_at, updated_at) FROM stdin;
1	1	certificados_senasag/k35IqXExcySZ3q4NzUIuAxphuHRrxFHLGc6ODw0n.png	0	2026-05-17 09:54:16	2026-05-17 09:54:16
2	2	certificados_senasag/bRA9OX5YPPYDLDHDKKwt7N6XPLf2hBHHU8VR9pUn.png	0	2026-05-17 09:54:16	2026-05-17 09:54:16
\.


--
-- Data for Name: imagenes_marca_ganado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.imagenes_marca_ganado (id, marca_animal_id, ruta, orden, created_at, updated_at) FROM stdin;
1	1	marcas_ganado/tMygv7yiOLmeR0LfQMZnDk0vQRdfR7oyTwIRAHX0.png	0	2026-05-17 09:37:21	2026-05-17 09:37:21
2	2	marcas_ganado/U3QS1uPF30hMUZLbhLA9ShnIn0e1Dut1hJ3FdZkl.png	0	2026-05-17 09:41:18	2026-05-17 09:41:18
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: logros_reconocimientos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.logros_reconocimientos (id, dato_sanitario_id, created_at, updated_at) FROM stdin;
1	1	2026-05-17 10:07:09	2026-05-17 10:07:09
2	2	2026-05-17 10:07:09	2026-05-17 10:07:09
\.


--
-- Data for Name: maquinaria_imagenes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.maquinaria_imagenes (id, maquinaria_id, ruta, orden, created_at, updated_at) FROM stdin;
1	1	maquinarias/C6aXwgxfIZ7BZoQduziaHZOrcbVAJHun4sCXvFLn.png	0	2026-05-15 03:07:17	2026-05-15 03:07:17
\.


--
-- Data for Name: maquinarias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.maquinarias (id, nombre, modelo, precio_dia, descripcion, categoria_id, created_at, updated_at, user_id, tipo_maquinaria_id, marca_maquinaria_id, telefono, estado_maquinaria_id, ubicacion_maquinaria_id) FROM stdin;
1	tractor	060600	1111.00	nose	1	2026-05-15 03:07:17	2026-05-15 03:12:12	1	1	1	12312313	3	1
\.


--
-- Data for Name: marcas_animales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.marcas_animales (id, dato_sanitario_id, marca_ganado, senal_numero, created_at, updated_at) FROM stdin;
1	1	aadsads	asada	2026-05-17 09:37:21	2026-05-17 09:37:21
2	2	sdfsfsdfs	sfdsfsfd	2026-05-17 09:41:18	2026-05-17 09:41:18
\.


--
-- Data for Name: marcas_maquinarias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.marcas_maquinarias (id, nombre, descripcion, created_at, updated_at) FROM stdin;
1	susuki	123	2026-05-15 03:06:13	2026-05-15 03:06:13
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_01_01_000100_create_categorias_table	1
5	2025_09_04_041312_create_maquinarias_table	1
6	2025_09_04_041312_create_organicos_table	1
7	2025_11_01_000000_create_tipo_animals_table	1
8	2025_11_02_000000_create_razas_table	1
9	2025_11_03_000000_create_tipo_pesos_table	1
10	2025_11_04_000000_create_ganados_table	1
11	2025_11_06_000000_create_datos_sanitarios_table	1
12	2025_11_18_215820_add_stock_to_ganados_table	1
13	2025_11_18_222343_add_role_to_users_table	1
14	2025_11_18_222351_create_solicitudes_vendedor_table	1
15	2025_11_18_225753_create_roles_table	1
16	2025_11_18_225758_modify_users_table_for_roles	1
17	2025_11_18_230432_add_fecha_revision_to_solicitudes_vendedor_table	1
18	2025_11_19_000645_add_user_id_to_ganados_table	1
19	2025_11_19_000649_add_user_id_to_maquinarias_table	1
20	2025_11_19_000655_add_user_id_to_organicos_table	1
21	2025_11_19_003149_create_tipo_maquinarias_table	1
22	2025_11_19_003217_add_tipo_maquinaria_id_to_maquinarias_table	1
23	2025_11_19_004356_create_marcas_maquinarias_table	1
24	2025_11_19_004413_add_marca_maquinaria_id_to_maquinarias_table	1
25	2025_11_20_160025_add_telefono_to_maquinarias_table	1
26	2025_11_20_160330_create_estado_maquinarias_table	1
27	2025_11_20_160514_add_estado_maquinaria_id_to_maquinarias_table	1
28	2025_11_24_235328_create_maquinaria_imagenes_table	1
29	2025_11_24_235655_add_ubicacion_to_maquinarias_table	1
30	2025_11_25_000321_create_unidades_organicos_table	1
31	2025_11_25_000424_add_unidad_id_to_organicos_table	1
32	2025_11_25_001303_add_origen_to_organicos_table	1
33	2025_11_25_001629_create_organico_imagenes_table	1
34	2025_11_25_003939_create_cart_items_table	1
35	2025_11_26_023416_add_dato_sanitario_id_to_ganados_table	1
36	2025_11_26_025545_add_certificado_imagen_and_vacunas_checkboxes_to_datos_sanitarios_table	1
37	2025_11_26_030651_create_guia_movimientos_table	1
38	2025_11_26_030732_add_guia_movimiento_to_datos_sanitarios_table	1
39	2025_11_26_031226_add_dueno_info_to_datos_sanitarios_table	1
40	2025_12_01_023520_drop_tipo_column_on_maquinarias	1
41	2025_12_01_024601_remove_marca_from_maquinarias_table	1
42	2025_12_01_223900_add_departamento_municipio_provincia_to_ganados_table	1
43	2025_12_01_225153_add_ciudad_to_ganados_table	1
44	2025_12_01_232021_create_ganado_imagenes_table	1
45	2025_12_02_005451_add_departamento_municipio_provincia_ciudad_to_maquinarias_table	1
46	2025_12_02_023203_make_ganado_id_nullable_in_datos_sanitarios_table	1
47	2025_12_02_024123_add_marca_ganado_foto_to_datos_sanitarios_table	1
48	2025_12_02_024939_add_user_id_to_datos_sanitarios_table	1
49	2025_12_02_072052_create_pedidos_table	1
50	2025_12_02_072053_create_pedido_detalles_table	1
51	2025_12_03_024158_add_peso_actual_to_ganados_table	1
52	2025_12_03_191035_add_campeon_info_to_ganados_table	1
53	2025_12_03_191047_add_certificado_campeon_to_datos_sanitarios_table	1
54	2025_12_03_193748_add_logros_and_arbol_genealogico_to_ganados_table	1
55	2025_12_04_045658_create_tipo_cultivos_table	1
56	2025_12_04_045731_add_tipo_cultivo_id_to_organicos_table	1
57	2025_12_12_194725_add_en_uso_estado_to_estado_maquinarias_table	1
58	2025_12_13_231446_rename_dueno_columns_in_datos_sanitarios_table	1
59	2026_05_15_000001_create_ubicacion_geografica_maquinarias_table	2
60	2026_05_15_000002_create_ubicacion_maquinaria_table	2
61	2026_05_15_000003_add_ubicacion_maquinaria_id_to_maquinarias_table	2
62	2026_05_15_000004_drop_old_location_columns_from_maquinarias_table	3
63	2026_05_15_000005_drop_estado_column_from_maquinarias_table	4
64	2026_05_15_000006_create_ubicacion_geografica_ganados_table	5
65	2026_05_15_000007_create_ubicacion_ganado_table	5
66	2026_05_15_000008_add_ubicacion_ganado_id_to_ganados_table	5
67	2026_05_15_000009_drop_old_location_columns_from_ganados_table	6
68	2026_05_17_000001_create_tratamientos_medicamentos_table	7
69	2026_05_17_000002_create_dato_sanitario_vacunaciones_table	7
70	2026_05_17_000003_create_marcas_animales_table	7
71	2026_05_17_000004_create_datos_duenos_table	7
72	2026_05_17_000005_drop_normalized_columns_from_datos_sanitarios_table	8
73	2026_05_17_000006_create_imagenes_dato_sanitario_vacunaciones_table	9
74	2026_05_17_000007_create_logros_reconocimientos_tables	10
75	2026_05_17_000008_create_archivos_reconocimiento_sanitario_tables	11
76	2026_05_17_000009_create_datos_productivos_ganado_table	12
77	2026_05_17_000010_create_datos_comerciales_ganado_table	12
78	2026_05_17_000011_create_caracteristicas_ganado_table	12
79	2026_05_17_000012_create_genealogias_ganado_table	12
80	2026_05_17_000013_drop_old_normalized_columns_from_ganados_table	13
81	2026_05_17_000014_drop_imagen_column_from_ganados_table	14
82	2026_05_17_000015_create_ubicacion_organico_tables	15
83	2026_05_17_000016_create_datos_comerciales_organicos_table	15
84	2026_05_17_000017_drop_old_normalized_columns_from_organicos_table	16
\.


--
-- Data for Name: organico_imagenes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.organico_imagenes (id, organico_id, ruta, orden, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: organicos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.organicos (id, nombre, categoria_id, fecha_cosecha, descripcion, created_at, updated_at, user_id, tipo_cultivo_id, ubicacion_organico_id) FROM stdin;
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: pedido_detalles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pedido_detalles (id, pedido_id, product_id, product_type, nombre_producto, cantidad, precio_unitario, subtotal, notas, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: pedidos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pedidos (id, user_id, total, estado, metodo_pago, observaciones, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: produccion_carnes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.produccion_carnes (id, logro_reconocimiento_id, logro_mejor_novillo, logro_gran_campeon_carne, logro_mejor_semental, created_at, updated_at) FROM stdin;
1	1	t	f	f	2026-05-17 10:07:09	2026-05-17 10:07:09
2	2	t	t	f	2026-05-17 10:07:09	2026-05-17 10:07:09
\.


--
-- Data for Name: produccion_leches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.produccion_leches (id, logro_reconocimiento_id, logro_campeona_litros_dia, logro_mejor_lactancia, logro_mejor_calidad_leche, created_at, updated_at) FROM stdin;
1	1	f	f	f	2026-05-17 10:07:09	2026-05-17 10:07:09
2	2	f	f	f	2026-05-17 10:07:09	2026-05-17 10:07:09
\.


--
-- Data for Name: razas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.razas (id, nombre, descripcion, tipo_animal_id, created_at, updated_at) FROM stdin;
1	raza	123	1	2026-05-15 08:18:40	2026-05-15 08:18:40
\.


--
-- Data for Name: reproduccion_logros; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reproduccion_logros (id, logro_reconocimiento_id, logro_mejor_madre, logro_mejor_padre, logro_mejor_fertilidad, created_at, updated_at) FROM stdin;
1	1	f	t	f	2026-05-17 10:07:09	2026-05-17 10:07:09
2	2	t	f	f	2026-05-17 10:07:09	2026-05-17 10:07:09
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, nombre, descripcion, created_at, updated_at) FROM stdin;
1	admin	Administrador con control total del sistema	2026-05-13 07:16:02	2026-05-13 07:16:02
2	vendedor	Vendedor que puede publicar productos	2026-05-13 07:16:02	2026-05-13 07:16:02
3	cliente	Cliente que puede ver productos y solicitar ser vendedor	2026-05-13 07:16:02	2026-05-13 07:16:02
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: solicitudes_vendedor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.solicitudes_vendedor (id, user_id, motivo, telefono, direccion, documento, archivo_documento, estado, created_at, updated_at, fecha_revision_admin) FROM stdin;
\.


--
-- Data for Name: tipo_animals; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_animals (id, nombre, descripcion, created_at, updated_at) FROM stdin;
1	tipoanimaltest	123	2026-05-15 08:17:59	2026-05-15 08:17:59
\.


--
-- Data for Name: tipo_cultivos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_cultivos (id, nombre, descripcion, created_at, updated_at) FROM stdin;
1	Hortalizas	Verduras de hoja, raíz y fruto	2026-05-13 07:16:02	2026-05-13 07:16:02
2	Frutas	Frutas frescas de temporada	2026-05-13 07:16:02	2026-05-13 07:16:02
3	Tubérculos	Papa, yuca, camote, etc.	2026-05-13 07:16:02	2026-05-13 07:16:02
4	Legumbres	Frejol, lenteja, garbanzo, etc.	2026-05-13 07:16:02	2026-05-13 07:16:02
5	Cereales	Maíz, trigo, arroz, etc.	2026-05-13 07:16:02	2026-05-13 07:16:02
6	Aromáticas	Hierbas aromáticas y medicinales	2026-05-13 07:16:02	2026-05-13 07:16:02
\.


--
-- Data for Name: tipo_maquinarias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_maquinarias (id, nombre, descripcion, created_at, updated_at) FROM stdin;
1	sdsdasd	asdadsasd	2026-05-15 03:06:35	2026-05-15 03:06:35
\.


--
-- Data for Name: tipo_pesos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_pesos (id, nombre, descripcion, created_at, updated_at) FROM stdin;
1	tipodepesotest	\N	2026-05-15 08:18:07	2026-05-15 08:18:07
\.


--
-- Data for Name: tratamientos_medicamentos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tratamientos_medicamentos (id, dato_sanitario_id, tratamiento, medicamento, fecha_aplicacion, proxima_fecha, veterinario, observaciones, created_at, updated_at) FROM stdin;
1	1	sfdsfs	sfsdsdfsf	2026-05-06	2026-06-18	hugo	dasdaasdadas	2026-05-17 09:37:20	2026-05-17 09:37:20
2	2	dfsfdsfsads	sdfsfsdasdadads	2026-05-13	2026-07-01	dfsfdsdfsadsadad	sfsfsasdadsa	2026-05-17 09:41:18	2026-05-17 09:42:52
\.


--
-- Data for Name: ubicacion_ganado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ubicacion_ganado (id, ubicacion, latitud, longitud, ubicacion_geografica_ganado_id, created_at, updated_at) FROM stdin;
1	Los Andes, Provincia Provincia Ichilo, Santa Cruz, Bolivia	-17.1456896	-63.9184570	1	2026-05-15 08:32:53	2026-05-15 08:32:53
2	Provincia Antonio Vaca Díez, Beni, Bolivia	-11.6034552	-65.3906250	2	2026-05-17 10:56:58	2026-05-17 10:56:58
\.


--
-- Data for Name: ubicacion_geografica_ganados; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ubicacion_geografica_ganados (id, departamento, municipio, provincia, ciudad, created_at, updated_at) FROM stdin;
1	Santa Cruz	Los Andes	Provincia Ichilo	Municipio San Juan	2026-05-15 08:32:53	2026-05-15 08:32:53
2	Beni	\N	Antonio Vaca Díez	Guayaramerín	2026-05-17 10:56:58	2026-05-17 10:56:58
\.


--
-- Data for Name: ubicacion_geografica_maquinarias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ubicacion_geografica_maquinarias (id, departamento, municipio, provincia, ciudad, created_at, updated_at) FROM stdin;
1	Boquerón	Mariscal Estigarribia	\N	Mariscal Estigarribia	2026-05-15 03:07:17	2026-05-15 03:07:17
2	Alto Paraguay	Bahía Negra	\N	Bahía Negra	2026-05-15 03:12:12	2026-05-15 03:12:12
\.


--
-- Data for Name: ubicacion_geografica_organicos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ubicacion_geografica_organicos (id, departamento, municipio, provincia, ciudad, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ubicacion_maquinaria; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ubicacion_maquinaria (id, ubicacion, latitud, longitud, ubicacion_geografica_maquinaria_id, created_at, updated_at) FROM stdin;
1	Alto Paraguay, Bolivia	-19.8680795	-61.4273071	2	2026-05-15 03:07:17	2026-05-15 03:12:12
\.


--
-- Data for Name: ubicacion_organico; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ubicacion_organico (id, ubicacion, latitud, longitud, ubicacion_geografica_organico_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: unidades_organicos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.unidades_organicos (id, nombre, descripcion, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role, role_id) FROM stdin;
1	Administrador	admin@agrovida.com	\N	$2y$12$ape3I1Z8U9kdfJlRzCYlTe2oCunGqy5POMjAJ7Kn/ymxrYYovPBd.	\N	2026-05-13 07:16:02	2026-05-13 07:16:02	cliente	1
\.


--
-- Name: archivos_arbol_genealogico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.archivos_arbol_genealogico_id_seq', 2, true);


--
-- Name: belleza_estructuras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.belleza_estructuras_id_seq', 2, true);


--
-- Name: caracteristicas_ganado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.caracteristicas_ganado_id_seq', 2, true);


--
-- Name: cart_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cart_items_id_seq', 1, false);


--
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 1, true);


--
-- Name: dato_sanitario_vacunaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dato_sanitario_vacunaciones_id_seq', 2, true);


--
-- Name: datos_comerciales_ganado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.datos_comerciales_ganado_id_seq', 2, true);


--
-- Name: datos_comerciales_organicos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.datos_comerciales_organicos_id_seq', 2, true);


--
-- Name: datos_duenos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.datos_duenos_id_seq', 2, true);


--
-- Name: datos_productivos_ganado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.datos_productivos_ganado_id_seq', 2, true);


--
-- Name: datos_sanitarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.datos_sanitarios_id_seq', 2, true);


--
-- Name: estado_maquinarias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.estado_maquinarias_id_seq', 4, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: ganado_imagenes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ganado_imagenes_id_seq', 2, true);


--
-- Name: ganados_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ganados_id_seq', 2, true);


--
-- Name: genealogias_ganado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.genealogias_ganado_id_seq', 2, true);


--
-- Name: guia_movimientos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.guia_movimientos_id_seq', 1, false);


--
-- Name: imagenes_certificado_campeon_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.imagenes_certificado_campeon_id_seq', 2, true);


--
-- Name: imagenes_dato_sanitario_vacunaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.imagenes_dato_sanitario_vacunaciones_id_seq', 2, true);


--
-- Name: imagenes_marca_ganado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.imagenes_marca_ganado_id_seq', 2, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: logros_reconocimientos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.logros_reconocimientos_id_seq', 2, true);


--
-- Name: maquinaria_imagenes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.maquinaria_imagenes_id_seq', 1, true);


--
-- Name: maquinarias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.maquinarias_id_seq', 1, true);


--
-- Name: marcas_animales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.marcas_animales_id_seq', 2, true);


--
-- Name: marcas_maquinarias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.marcas_maquinarias_id_seq', 1, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 84, true);


--
-- Name: organico_imagenes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.organico_imagenes_id_seq', 1, false);


--
-- Name: organicos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.organicos_id_seq', 2, true);


--
-- Name: pedido_detalles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pedido_detalles_id_seq', 1, false);


--
-- Name: pedidos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pedidos_id_seq', 1, false);


--
-- Name: produccion_carnes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.produccion_carnes_id_seq', 2, true);


--
-- Name: produccion_leches_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.produccion_leches_id_seq', 2, true);


--
-- Name: razas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.razas_id_seq', 1, true);


--
-- Name: reproduccion_logros_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reproduccion_logros_id_seq', 2, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 3, true);


--
-- Name: solicitudes_vendedor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.solicitudes_vendedor_id_seq', 1, false);


--
-- Name: tipo_animals_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_animals_id_seq', 1, true);


--
-- Name: tipo_cultivos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_cultivos_id_seq', 6, true);


--
-- Name: tipo_maquinarias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_maquinarias_id_seq', 1, true);


--
-- Name: tipo_pesos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_pesos_id_seq', 1, true);


--
-- Name: tratamientos_medicamentos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tratamientos_medicamentos_id_seq', 2, true);


--
-- Name: ubicacion_ganado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ubicacion_ganado_id_seq', 2, true);


--
-- Name: ubicacion_geografica_ganados_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ubicacion_geografica_ganados_id_seq', 2, true);


--
-- Name: ubicacion_geografica_maquinarias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ubicacion_geografica_maquinarias_id_seq', 2, true);


--
-- Name: ubicacion_geografica_organicos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ubicacion_geografica_organicos_id_seq', 2, true);


--
-- Name: ubicacion_maquinaria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ubicacion_maquinaria_id_seq', 1, true);


--
-- Name: ubicacion_organico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ubicacion_organico_id_seq', 2, true);


--
-- Name: unidades_organicos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.unidades_organicos_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: archivos_arbol_genealogico archivos_arbol_genealogico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archivos_arbol_genealogico
    ADD CONSTRAINT archivos_arbol_genealogico_pkey PRIMARY KEY (id);


--
-- Name: belleza_estructuras belleza_estructuras_logro_reconocimiento_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.belleza_estructuras
    ADD CONSTRAINT belleza_estructuras_logro_reconocimiento_id_unique UNIQUE (logro_reconocimiento_id);


--
-- Name: belleza_estructuras belleza_estructuras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.belleza_estructuras
    ADD CONSTRAINT belleza_estructuras_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: caracteristicas_ganado caracteristicas_ganado_ganado_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.caracteristicas_ganado
    ADD CONSTRAINT caracteristicas_ganado_ganado_id_unique UNIQUE (ganado_id);


--
-- Name: caracteristicas_ganado caracteristicas_ganado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.caracteristicas_ganado
    ADD CONSTRAINT caracteristicas_ganado_pkey PRIMARY KEY (id);


--
-- Name: cart_items cart_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_pkey PRIMARY KEY (id);


--
-- Name: cart_items cart_items_user_id_product_type_product_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_user_id_product_type_product_id_unique UNIQUE (user_id, product_type, product_id);


--
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);


--
-- Name: dato_sanitario_vacunaciones dato_sanitario_vacunaciones_dato_sanitario_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dato_sanitario_vacunaciones
    ADD CONSTRAINT dato_sanitario_vacunaciones_dato_sanitario_id_unique UNIQUE (dato_sanitario_id);


--
-- Name: dato_sanitario_vacunaciones dato_sanitario_vacunaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dato_sanitario_vacunaciones
    ADD CONSTRAINT dato_sanitario_vacunaciones_pkey PRIMARY KEY (id);


--
-- Name: datos_comerciales_ganado datos_comerciales_ganado_ganado_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_comerciales_ganado
    ADD CONSTRAINT datos_comerciales_ganado_ganado_id_unique UNIQUE (ganado_id);


--
-- Name: datos_comerciales_ganado datos_comerciales_ganado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_comerciales_ganado
    ADD CONSTRAINT datos_comerciales_ganado_pkey PRIMARY KEY (id);


--
-- Name: datos_comerciales_organicos datos_comerciales_organicos_organico_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_comerciales_organicos
    ADD CONSTRAINT datos_comerciales_organicos_organico_id_unique UNIQUE (organico_id);


--
-- Name: datos_comerciales_organicos datos_comerciales_organicos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_comerciales_organicos
    ADD CONSTRAINT datos_comerciales_organicos_pkey PRIMARY KEY (id);


--
-- Name: datos_duenos datos_duenos_dato_sanitario_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_duenos
    ADD CONSTRAINT datos_duenos_dato_sanitario_id_unique UNIQUE (dato_sanitario_id);


--
-- Name: datos_duenos datos_duenos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_duenos
    ADD CONSTRAINT datos_duenos_pkey PRIMARY KEY (id);


--
-- Name: datos_productivos_ganado datos_productivos_ganado_ganado_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_productivos_ganado
    ADD CONSTRAINT datos_productivos_ganado_ganado_id_unique UNIQUE (ganado_id);


--
-- Name: datos_productivos_ganado datos_productivos_ganado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_productivos_ganado
    ADD CONSTRAINT datos_productivos_ganado_pkey PRIMARY KEY (id);


--
-- Name: datos_sanitarios datos_sanitarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_sanitarios
    ADD CONSTRAINT datos_sanitarios_pkey PRIMARY KEY (id);


--
-- Name: estado_maquinarias estado_maquinarias_nombre_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.estado_maquinarias
    ADD CONSTRAINT estado_maquinarias_nombre_unique UNIQUE (nombre);


--
-- Name: estado_maquinarias estado_maquinarias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.estado_maquinarias
    ADD CONSTRAINT estado_maquinarias_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: ganado_imagenes ganado_imagenes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_imagenes
    ADD CONSTRAINT ganado_imagenes_pkey PRIMARY KEY (id);


--
-- Name: ganados ganados_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados
    ADD CONSTRAINT ganados_pkey PRIMARY KEY (id);


--
-- Name: genealogias_ganado genealogias_ganado_ganado_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.genealogias_ganado
    ADD CONSTRAINT genealogias_ganado_ganado_id_unique UNIQUE (ganado_id);


--
-- Name: genealogias_ganado genealogias_ganado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.genealogias_ganado
    ADD CONSTRAINT genealogias_ganado_pkey PRIMARY KEY (id);


--
-- Name: guia_movimientos guia_movimientos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.guia_movimientos
    ADD CONSTRAINT guia_movimientos_pkey PRIMARY KEY (id);


--
-- Name: imagenes_certificado_campeon imagenes_certificado_campeon_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.imagenes_certificado_campeon
    ADD CONSTRAINT imagenes_certificado_campeon_pkey PRIMARY KEY (id);


--
-- Name: imagenes_dato_sanitario_vacunaciones imagenes_dato_sanitario_vacunaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.imagenes_dato_sanitario_vacunaciones
    ADD CONSTRAINT imagenes_dato_sanitario_vacunaciones_pkey PRIMARY KEY (id);


--
-- Name: imagenes_marca_ganado imagenes_marca_ganado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.imagenes_marca_ganado
    ADD CONSTRAINT imagenes_marca_ganado_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: logros_reconocimientos logros_reconocimientos_dato_sanitario_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logros_reconocimientos
    ADD CONSTRAINT logros_reconocimientos_dato_sanitario_id_unique UNIQUE (dato_sanitario_id);


--
-- Name: logros_reconocimientos logros_reconocimientos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logros_reconocimientos
    ADD CONSTRAINT logros_reconocimientos_pkey PRIMARY KEY (id);


--
-- Name: maquinaria_imagenes maquinaria_imagenes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinaria_imagenes
    ADD CONSTRAINT maquinaria_imagenes_pkey PRIMARY KEY (id);


--
-- Name: maquinarias maquinarias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinarias
    ADD CONSTRAINT maquinarias_pkey PRIMARY KEY (id);


--
-- Name: marcas_animales marcas_animales_dato_sanitario_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marcas_animales
    ADD CONSTRAINT marcas_animales_dato_sanitario_id_unique UNIQUE (dato_sanitario_id);


--
-- Name: marcas_animales marcas_animales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marcas_animales
    ADD CONSTRAINT marcas_animales_pkey PRIMARY KEY (id);


--
-- Name: marcas_maquinarias marcas_maquinarias_nombre_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marcas_maquinarias
    ADD CONSTRAINT marcas_maquinarias_nombre_unique UNIQUE (nombre);


--
-- Name: marcas_maquinarias marcas_maquinarias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marcas_maquinarias
    ADD CONSTRAINT marcas_maquinarias_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: organico_imagenes organico_imagenes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organico_imagenes
    ADD CONSTRAINT organico_imagenes_pkey PRIMARY KEY (id);


--
-- Name: organicos organicos_nombre_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organicos
    ADD CONSTRAINT organicos_nombre_unique UNIQUE (nombre);


--
-- Name: organicos organicos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organicos
    ADD CONSTRAINT organicos_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: pedido_detalles pedido_detalles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedido_detalles
    ADD CONSTRAINT pedido_detalles_pkey PRIMARY KEY (id);


--
-- Name: pedidos pedidos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos
    ADD CONSTRAINT pedidos_pkey PRIMARY KEY (id);


--
-- Name: produccion_carnes produccion_carnes_logro_reconocimiento_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produccion_carnes
    ADD CONSTRAINT produccion_carnes_logro_reconocimiento_id_unique UNIQUE (logro_reconocimiento_id);


--
-- Name: produccion_carnes produccion_carnes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produccion_carnes
    ADD CONSTRAINT produccion_carnes_pkey PRIMARY KEY (id);


--
-- Name: produccion_leches produccion_leches_logro_reconocimiento_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produccion_leches
    ADD CONSTRAINT produccion_leches_logro_reconocimiento_id_unique UNIQUE (logro_reconocimiento_id);


--
-- Name: produccion_leches produccion_leches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produccion_leches
    ADD CONSTRAINT produccion_leches_pkey PRIMARY KEY (id);


--
-- Name: razas razas_nombre_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.razas
    ADD CONSTRAINT razas_nombre_unique UNIQUE (nombre);


--
-- Name: razas razas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.razas
    ADD CONSTRAINT razas_pkey PRIMARY KEY (id);


--
-- Name: reproduccion_logros reproduccion_logros_logro_reconocimiento_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reproduccion_logros
    ADD CONSTRAINT reproduccion_logros_logro_reconocimiento_id_unique UNIQUE (logro_reconocimiento_id);


--
-- Name: reproduccion_logros reproduccion_logros_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reproduccion_logros
    ADD CONSTRAINT reproduccion_logros_pkey PRIMARY KEY (id);


--
-- Name: roles roles_nombre_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_nombre_unique UNIQUE (nombre);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: solicitudes_vendedor solicitudes_vendedor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitudes_vendedor
    ADD CONSTRAINT solicitudes_vendedor_pkey PRIMARY KEY (id);


--
-- Name: tipo_animals tipo_animals_nombre_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_animals
    ADD CONSTRAINT tipo_animals_nombre_unique UNIQUE (nombre);


--
-- Name: tipo_animals tipo_animals_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_animals
    ADD CONSTRAINT tipo_animals_pkey PRIMARY KEY (id);


--
-- Name: tipo_cultivos tipo_cultivos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_cultivos
    ADD CONSTRAINT tipo_cultivos_pkey PRIMARY KEY (id);


--
-- Name: tipo_maquinarias tipo_maquinarias_nombre_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_maquinarias
    ADD CONSTRAINT tipo_maquinarias_nombre_unique UNIQUE (nombre);


--
-- Name: tipo_maquinarias tipo_maquinarias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_maquinarias
    ADD CONSTRAINT tipo_maquinarias_pkey PRIMARY KEY (id);


--
-- Name: tipo_pesos tipo_pesos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_pesos
    ADD CONSTRAINT tipo_pesos_pkey PRIMARY KEY (id);


--
-- Name: tratamientos_medicamentos tratamientos_medicamentos_dato_sanitario_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tratamientos_medicamentos
    ADD CONSTRAINT tratamientos_medicamentos_dato_sanitario_id_unique UNIQUE (dato_sanitario_id);


--
-- Name: tratamientos_medicamentos tratamientos_medicamentos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tratamientos_medicamentos
    ADD CONSTRAINT tratamientos_medicamentos_pkey PRIMARY KEY (id);


--
-- Name: ubicacion_ganado ubicacion_ganado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_ganado
    ADD CONSTRAINT ubicacion_ganado_pkey PRIMARY KEY (id);


--
-- Name: ubicacion_geografica_ganados ubicacion_geo_ganado_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_geografica_ganados
    ADD CONSTRAINT ubicacion_geo_ganado_unique UNIQUE (departamento, municipio, provincia, ciudad);


--
-- Name: ubicacion_geografica_maquinarias ubicacion_geo_maq_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_geografica_maquinarias
    ADD CONSTRAINT ubicacion_geo_maq_unique UNIQUE (departamento, municipio, provincia, ciudad);


--
-- Name: ubicacion_geografica_organicos ubicacion_geo_org_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_geografica_organicos
    ADD CONSTRAINT ubicacion_geo_org_unique UNIQUE (departamento, municipio, provincia, ciudad);


--
-- Name: ubicacion_geografica_ganados ubicacion_geografica_ganados_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_geografica_ganados
    ADD CONSTRAINT ubicacion_geografica_ganados_pkey PRIMARY KEY (id);


--
-- Name: ubicacion_geografica_maquinarias ubicacion_geografica_maquinarias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_geografica_maquinarias
    ADD CONSTRAINT ubicacion_geografica_maquinarias_pkey PRIMARY KEY (id);


--
-- Name: ubicacion_geografica_organicos ubicacion_geografica_organicos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_geografica_organicos
    ADD CONSTRAINT ubicacion_geografica_organicos_pkey PRIMARY KEY (id);


--
-- Name: ubicacion_maquinaria ubicacion_maquinaria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_maquinaria
    ADD CONSTRAINT ubicacion_maquinaria_pkey PRIMARY KEY (id);


--
-- Name: ubicacion_organico ubicacion_organico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_organico
    ADD CONSTRAINT ubicacion_organico_pkey PRIMARY KEY (id);


--
-- Name: unidades_organicos unidades_organicos_nombre_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.unidades_organicos
    ADD CONSTRAINT unidades_organicos_nombre_unique UNIQUE (nombre);


--
-- Name: unidades_organicos unidades_organicos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.unidades_organicos
    ADD CONSTRAINT unidades_organicos_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: solicitudes_vendedor_user_id_estado_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX solicitudes_vendedor_user_id_estado_index ON public.solicitudes_vendedor USING btree (user_id, estado);


--
-- Name: archivos_arbol_genealogico archivos_arbol_genealogico_dato_sanitario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archivos_arbol_genealogico
    ADD CONSTRAINT archivos_arbol_genealogico_dato_sanitario_id_foreign FOREIGN KEY (dato_sanitario_id) REFERENCES public.datos_sanitarios(id) ON DELETE CASCADE;


--
-- Name: belleza_estructuras belleza_estructuras_logro_reconocimiento_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.belleza_estructuras
    ADD CONSTRAINT belleza_estructuras_logro_reconocimiento_id_foreign FOREIGN KEY (logro_reconocimiento_id) REFERENCES public.logros_reconocimientos(id) ON DELETE CASCADE;


--
-- Name: caracteristicas_ganado caracteristicas_ganado_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.caracteristicas_ganado
    ADD CONSTRAINT caracteristicas_ganado_ganado_id_foreign FOREIGN KEY (ganado_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: cart_items cart_items_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: dato_sanitario_vacunaciones dato_sanitario_vacunaciones_dato_sanitario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dato_sanitario_vacunaciones
    ADD CONSTRAINT dato_sanitario_vacunaciones_dato_sanitario_id_foreign FOREIGN KEY (dato_sanitario_id) REFERENCES public.datos_sanitarios(id) ON DELETE CASCADE;


--
-- Name: datos_comerciales_ganado datos_comerciales_ganado_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_comerciales_ganado
    ADD CONSTRAINT datos_comerciales_ganado_ganado_id_foreign FOREIGN KEY (ganado_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: datos_comerciales_organicos datos_comerciales_organicos_organico_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_comerciales_organicos
    ADD CONSTRAINT datos_comerciales_organicos_organico_id_foreign FOREIGN KEY (organico_id) REFERENCES public.organicos(id) ON DELETE CASCADE;


--
-- Name: datos_comerciales_organicos datos_comerciales_organicos_unidad_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_comerciales_organicos
    ADD CONSTRAINT datos_comerciales_organicos_unidad_id_foreign FOREIGN KEY (unidad_id) REFERENCES public.unidades_organicos(id) ON DELETE SET NULL;


--
-- Name: datos_duenos datos_duenos_dato_sanitario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_duenos
    ADD CONSTRAINT datos_duenos_dato_sanitario_id_foreign FOREIGN KEY (dato_sanitario_id) REFERENCES public.datos_sanitarios(id) ON DELETE CASCADE;


--
-- Name: datos_productivos_ganado datos_productivos_ganado_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_productivos_ganado
    ADD CONSTRAINT datos_productivos_ganado_ganado_id_foreign FOREIGN KEY (ganado_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: datos_productivos_ganado datos_productivos_ganado_tipo_peso_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_productivos_ganado
    ADD CONSTRAINT datos_productivos_ganado_tipo_peso_id_foreign FOREIGN KEY (tipo_peso_id) REFERENCES public.tipo_pesos(id) ON DELETE SET NULL;


--
-- Name: datos_sanitarios datos_sanitarios_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_sanitarios
    ADD CONSTRAINT datos_sanitarios_ganado_id_foreign FOREIGN KEY (ganado_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: datos_sanitarios datos_sanitarios_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datos_sanitarios
    ADD CONSTRAINT datos_sanitarios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: ganado_imagenes ganado_imagenes_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_imagenes
    ADD CONSTRAINT ganado_imagenes_ganado_id_foreign FOREIGN KEY (ganado_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: ganados ganados_categoria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados
    ADD CONSTRAINT ganados_categoria_id_foreign FOREIGN KEY (categoria_id) REFERENCES public.categorias(id) ON DELETE CASCADE;


--
-- Name: ganados ganados_dato_sanitario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados
    ADD CONSTRAINT ganados_dato_sanitario_id_foreign FOREIGN KEY (dato_sanitario_id) REFERENCES public.datos_sanitarios(id) ON DELETE SET NULL;


--
-- Name: ganados ganados_raza_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados
    ADD CONSTRAINT ganados_raza_id_foreign FOREIGN KEY (raza_id) REFERENCES public.razas(id) ON DELETE SET NULL;


--
-- Name: ganados ganados_tipo_animal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados
    ADD CONSTRAINT ganados_tipo_animal_id_foreign FOREIGN KEY (tipo_animal_id) REFERENCES public.tipo_animals(id) ON DELETE CASCADE;


--
-- Name: ganados ganados_ubicacion_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados
    ADD CONSTRAINT ganados_ubicacion_ganado_id_foreign FOREIGN KEY (ubicacion_ganado_id) REFERENCES public.ubicacion_ganado(id) ON DELETE SET NULL;


--
-- Name: ganados ganados_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados
    ADD CONSTRAINT ganados_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: genealogias_ganado genealogias_ganado_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.genealogias_ganado
    ADD CONSTRAINT genealogias_ganado_ganado_id_foreign FOREIGN KEY (ganado_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: genealogias_ganado genealogias_ganado_madre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.genealogias_ganado
    ADD CONSTRAINT genealogias_ganado_madre_id_foreign FOREIGN KEY (madre_id) REFERENCES public.ganados(id) ON DELETE SET NULL;


--
-- Name: genealogias_ganado genealogias_ganado_padre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.genealogias_ganado
    ADD CONSTRAINT genealogias_ganado_padre_id_foreign FOREIGN KEY (padre_id) REFERENCES public.ganados(id) ON DELETE SET NULL;


--
-- Name: imagenes_certificado_campeon imagenes_certificado_campeon_dato_sanitario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.imagenes_certificado_campeon
    ADD CONSTRAINT imagenes_certificado_campeon_dato_sanitario_id_foreign FOREIGN KEY (dato_sanitario_id) REFERENCES public.datos_sanitarios(id) ON DELETE CASCADE;


--
-- Name: imagenes_dato_sanitario_vacunaciones imagenes_dato_sanitario_vacunaciones_dato_sanitario_vacunacion_; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.imagenes_dato_sanitario_vacunaciones
    ADD CONSTRAINT imagenes_dato_sanitario_vacunaciones_dato_sanitario_vacunacion_ FOREIGN KEY (dato_sanitario_vacunacion_id) REFERENCES public.dato_sanitario_vacunaciones(id) ON DELETE CASCADE;


--
-- Name: imagenes_marca_ganado imagenes_marca_ganado_marca_animal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.imagenes_marca_ganado
    ADD CONSTRAINT imagenes_marca_ganado_marca_animal_id_foreign FOREIGN KEY (marca_animal_id) REFERENCES public.marcas_animales(id) ON DELETE CASCADE;


--
-- Name: logros_reconocimientos logros_reconocimientos_dato_sanitario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logros_reconocimientos
    ADD CONSTRAINT logros_reconocimientos_dato_sanitario_id_foreign FOREIGN KEY (dato_sanitario_id) REFERENCES public.datos_sanitarios(id) ON DELETE CASCADE;


--
-- Name: maquinaria_imagenes maquinaria_imagenes_maquinaria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinaria_imagenes
    ADD CONSTRAINT maquinaria_imagenes_maquinaria_id_foreign FOREIGN KEY (maquinaria_id) REFERENCES public.maquinarias(id) ON DELETE CASCADE;


--
-- Name: maquinarias maquinarias_categoria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinarias
    ADD CONSTRAINT maquinarias_categoria_id_foreign FOREIGN KEY (categoria_id) REFERENCES public.categorias(id) ON DELETE CASCADE;


--
-- Name: maquinarias maquinarias_estado_maquinaria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinarias
    ADD CONSTRAINT maquinarias_estado_maquinaria_id_foreign FOREIGN KEY (estado_maquinaria_id) REFERENCES public.estado_maquinarias(id) ON DELETE SET NULL;


--
-- Name: maquinarias maquinarias_marca_maquinaria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinarias
    ADD CONSTRAINT maquinarias_marca_maquinaria_id_foreign FOREIGN KEY (marca_maquinaria_id) REFERENCES public.marcas_maquinarias(id) ON DELETE CASCADE;


--
-- Name: maquinarias maquinarias_tipo_maquinaria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinarias
    ADD CONSTRAINT maquinarias_tipo_maquinaria_id_foreign FOREIGN KEY (tipo_maquinaria_id) REFERENCES public.tipo_maquinarias(id) ON DELETE CASCADE;


--
-- Name: maquinarias maquinarias_ubicacion_maquinaria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinarias
    ADD CONSTRAINT maquinarias_ubicacion_maquinaria_id_foreign FOREIGN KEY (ubicacion_maquinaria_id) REFERENCES public.ubicacion_maquinaria(id) ON DELETE SET NULL;


--
-- Name: maquinarias maquinarias_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinarias
    ADD CONSTRAINT maquinarias_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: marcas_animales marcas_animales_dato_sanitario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marcas_animales
    ADD CONSTRAINT marcas_animales_dato_sanitario_id_foreign FOREIGN KEY (dato_sanitario_id) REFERENCES public.datos_sanitarios(id) ON DELETE CASCADE;


--
-- Name: organico_imagenes organico_imagenes_organico_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organico_imagenes
    ADD CONSTRAINT organico_imagenes_organico_id_foreign FOREIGN KEY (organico_id) REFERENCES public.organicos(id) ON DELETE CASCADE;


--
-- Name: organicos organicos_categoria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organicos
    ADD CONSTRAINT organicos_categoria_id_foreign FOREIGN KEY (categoria_id) REFERENCES public.categorias(id) ON DELETE CASCADE;


--
-- Name: organicos organicos_tipo_cultivo_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organicos
    ADD CONSTRAINT organicos_tipo_cultivo_id_foreign FOREIGN KEY (tipo_cultivo_id) REFERENCES public.tipo_cultivos(id) ON DELETE SET NULL;


--
-- Name: organicos organicos_ubicacion_organico_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organicos
    ADD CONSTRAINT organicos_ubicacion_organico_id_foreign FOREIGN KEY (ubicacion_organico_id) REFERENCES public.ubicacion_organico(id) ON DELETE SET NULL;


--
-- Name: organicos organicos_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organicos
    ADD CONSTRAINT organicos_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: pedido_detalles pedido_detalles_pedido_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedido_detalles
    ADD CONSTRAINT pedido_detalles_pedido_id_foreign FOREIGN KEY (pedido_id) REFERENCES public.pedidos(id) ON DELETE CASCADE;


--
-- Name: pedidos pedidos_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos
    ADD CONSTRAINT pedidos_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: produccion_carnes produccion_carnes_logro_reconocimiento_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produccion_carnes
    ADD CONSTRAINT produccion_carnes_logro_reconocimiento_id_foreign FOREIGN KEY (logro_reconocimiento_id) REFERENCES public.logros_reconocimientos(id) ON DELETE CASCADE;


--
-- Name: produccion_leches produccion_leches_logro_reconocimiento_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produccion_leches
    ADD CONSTRAINT produccion_leches_logro_reconocimiento_id_foreign FOREIGN KEY (logro_reconocimiento_id) REFERENCES public.logros_reconocimientos(id) ON DELETE CASCADE;


--
-- Name: razas razas_tipo_animal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.razas
    ADD CONSTRAINT razas_tipo_animal_id_foreign FOREIGN KEY (tipo_animal_id) REFERENCES public.tipo_animals(id) ON DELETE CASCADE;


--
-- Name: reproduccion_logros reproduccion_logros_logro_reconocimiento_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reproduccion_logros
    ADD CONSTRAINT reproduccion_logros_logro_reconocimiento_id_foreign FOREIGN KEY (logro_reconocimiento_id) REFERENCES public.logros_reconocimientos(id) ON DELETE CASCADE;


--
-- Name: solicitudes_vendedor solicitudes_vendedor_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitudes_vendedor
    ADD CONSTRAINT solicitudes_vendedor_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: tratamientos_medicamentos tratamientos_medicamentos_dato_sanitario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tratamientos_medicamentos
    ADD CONSTRAINT tratamientos_medicamentos_dato_sanitario_id_foreign FOREIGN KEY (dato_sanitario_id) REFERENCES public.datos_sanitarios(id) ON DELETE CASCADE;


--
-- Name: ubicacion_ganado ubicacion_ganado_ubicacion_geografica_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_ganado
    ADD CONSTRAINT ubicacion_ganado_ubicacion_geografica_ganado_id_foreign FOREIGN KEY (ubicacion_geografica_ganado_id) REFERENCES public.ubicacion_geografica_ganados(id) ON DELETE SET NULL;


--
-- Name: ubicacion_maquinaria ubicacion_maquinaria_ubicacion_geografica_maquinaria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_maquinaria
    ADD CONSTRAINT ubicacion_maquinaria_ubicacion_geografica_maquinaria_id_foreign FOREIGN KEY (ubicacion_geografica_maquinaria_id) REFERENCES public.ubicacion_geografica_maquinarias(id) ON DELETE SET NULL;


--
-- Name: ubicacion_organico ubicacion_organico_ubicacion_geografica_organico_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicacion_organico
    ADD CONSTRAINT ubicacion_organico_ubicacion_geografica_organico_id_foreign FOREIGN KEY (ubicacion_geografica_organico_id) REFERENCES public.ubicacion_geografica_organicos(id) ON DELETE SET NULL;


--
-- Name: users users_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict d8tBouH6Bpzh0IhmJhpvmeLqUCMkbJFabsy5sQdJ6IFaf8gJtoemk6HBFqntjRc

