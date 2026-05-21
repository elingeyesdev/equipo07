--
-- PostgreSQL database dump
--

\restrict wjDOIjasuoUv4hueGepz6hOvOV3ynHO3mgzek0vw2OtzUNFeIjORozwh1uhWfDO

-- Dumped from database version 16.14 (Debian 16.14-1.pgdg13+1)
-- Dumped by pg_dump version 16.14 (Debian 16.14-1.pgdg13+1)

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
    updated_at timestamp(0) without time zone,
    tipo character varying(255) DEFAULT 'general'::character varying NOT NULL,
    CONSTRAINT categorias_tipo_check CHECK (((tipo)::text = ANY ((ARRAY['ganado'::character varying, 'maquinaria'::character varying, 'organico'::character varying, 'general'::character varying])::text[])))
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
    hoja_ruta_foto character varying(255)
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
-- Name: ganado_documentos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ganado_documentos (
    id bigint NOT NULL,
    ganado_id bigint NOT NULL,
    tipo_documento character varying(255) NOT NULL,
    ruta character varying(255) NOT NULL,
    descripcion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ganado_documentos OWNER TO postgres;

--
-- Name: ganado_documentos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ganado_documentos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ganado_documentos_id_seq OWNER TO postgres;

--
-- Name: ganado_documentos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ganado_documentos_id_seq OWNED BY public.ganado_documentos.id;


--
-- Name: ganado_genealogias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ganado_genealogias (
    id bigint NOT NULL,
    ganado_id bigint NOT NULL,
    pariente_id bigint NOT NULL,
    tipo_relacion character varying(255) NOT NULL,
    observaciones text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ganado_genealogias OWNER TO postgres;

--
-- Name: ganado_genealogias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ganado_genealogias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ganado_genealogias_id_seq OWNER TO postgres;

--
-- Name: ganado_genealogias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ganado_genealogias_id_seq OWNED BY public.ganado_genealogias.id;


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
-- Name: ganado_logros; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ganado_logros (
    id bigint NOT NULL,
    ganado_id bigint NOT NULL,
    tipo_logro character varying(255) NOT NULL,
    descripcion text,
    certificado_imagen character varying(255),
    fecha_logro date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ganado_logros OWNER TO postgres;

--
-- Name: ganado_logros_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ganado_logros_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ganado_logros_id_seq OWNER TO postgres;

--
-- Name: ganado_logros_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ganado_logros_id_seq OWNED BY public.ganado_logros.id;


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
    es_campeon boolean DEFAULT false NOT NULL,
    ubicacion_id bigint,
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
    ubicacion_id bigint,
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
-- Name: organico_trazabilidades; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.organico_trazabilidades (
    id bigint NOT NULL,
    organico_id bigint NOT NULL,
    origen character varying(255) NOT NULL,
    finca character varying(255) NOT NULL,
    ubicacion character varying(255) NOT NULL,
    fecha_siembra date NOT NULL,
    fecha_cosecha date NOT NULL,
    tratamientos_utilizados text NOT NULL,
    certificaciones text NOT NULL,
    observaciones text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.organico_trazabilidades OWNER TO postgres;

--
-- Name: organico_trazabilidades_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.organico_trazabilidades_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.organico_trazabilidades_id_seq OWNER TO postgres;

--
-- Name: organico_trazabilidades_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.organico_trazabilidades_id_seq OWNED BY public.organico_trazabilidades.id;


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
    ubicacion_id bigint,
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
    updated_at timestamp(0) without time zone,
    publicacion_id bigint
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
-- Name: publicaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.publicaciones (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    publicable_type character varying(255) NOT NULL,
    publicable_id bigint NOT NULL,
    titulo character varying(255) NOT NULL,
    descripcion text,
    precio numeric(10,2) NOT NULL,
    stock integer DEFAULT 1 NOT NULL,
    estado character varying(255) DEFAULT 'activo'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.publicaciones OWNER TO postgres;

--
-- Name: publicaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.publicaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.publicaciones_id_seq OWNER TO postgres;

--
-- Name: publicaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.publicaciones_id_seq OWNED BY public.publicaciones.id;


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
-- Name: ubicaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ubicaciones (
    id bigint NOT NULL,
    departamento character varying(255),
    provincia character varying(255),
    municipio character varying(255),
    ciudad character varying(255),
    direccion character varying(255),
    latitud numeric(10,8),
    longitud numeric(11,8),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ubicaciones OWNER TO postgres;

--
-- Name: ubicaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ubicaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ubicaciones_id_seq OWNER TO postgres;

--
-- Name: ubicaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ubicaciones_id_seq OWNED BY public.ubicaciones.id;


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
-- Name: ganado_documentos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_documentos ALTER COLUMN id SET DEFAULT nextval('public.ganado_documentos_id_seq'::regclass);


--
-- Name: ganado_genealogias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_genealogias ALTER COLUMN id SET DEFAULT nextval('public.ganado_genealogias_id_seq'::regclass);


--
-- Name: ganado_imagenes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_imagenes ALTER COLUMN id SET DEFAULT nextval('public.ganado_imagenes_id_seq'::regclass);


--
-- Name: ganado_logros id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_logros ALTER COLUMN id SET DEFAULT nextval('public.ganado_logros_id_seq'::regclass);


--
-- Name: ganados id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados ALTER COLUMN id SET DEFAULT nextval('public.ganados_id_seq'::regclass);


--
-- Name: genealogias_ganado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.genealogias_ganado ALTER COLUMN id SET DEFAULT nextval('public.genealogias_ganado_id_seq'::regclass);


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
-- Name: organico_trazabilidades id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organico_trazabilidades ALTER COLUMN id SET DEFAULT nextval('public.organico_trazabilidades_id_seq'::regclass);


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
-- Name: publicaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.publicaciones ALTER COLUMN id SET DEFAULT nextval('public.publicaciones_id_seq'::regclass);


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
-- Name: ubicaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicaciones ALTER COLUMN id SET DEFAULT nextval('public.ubicaciones_id_seq'::regclass);


--
-- Name: unidades_organicos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.unidades_organicos ALTER COLUMN id SET DEFAULT nextval('public.unidades_organicos_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


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
-- Name: ganado_documentos ganado_documentos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_documentos
    ADD CONSTRAINT ganado_documentos_pkey PRIMARY KEY (id);


--
-- Name: ganado_genealogias ganado_genealogias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_genealogias
    ADD CONSTRAINT ganado_genealogias_pkey PRIMARY KEY (id);


--
-- Name: ganado_imagenes ganado_imagenes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_imagenes
    ADD CONSTRAINT ganado_imagenes_pkey PRIMARY KEY (id);


--
-- Name: ganado_logros ganado_logros_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_logros
    ADD CONSTRAINT ganado_logros_pkey PRIMARY KEY (id);


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
-- Name: organico_trazabilidades organico_trazabilidades_organico_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organico_trazabilidades
    ADD CONSTRAINT organico_trazabilidades_organico_id_unique UNIQUE (organico_id);


--
-- Name: organico_trazabilidades organico_trazabilidades_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organico_trazabilidades
    ADD CONSTRAINT organico_trazabilidades_pkey PRIMARY KEY (id);


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
-- Name: publicaciones publicaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.publicaciones
    ADD CONSTRAINT publicaciones_pkey PRIMARY KEY (id);


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
-- Name: ubicaciones ubicaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ubicaciones
    ADD CONSTRAINT ubicaciones_pkey PRIMARY KEY (id);


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
-- Name: publicaciones_publicable_type_publicable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX publicaciones_publicable_type_publicable_id_index ON public.publicaciones USING btree (publicable_type, publicable_id);


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
-- Name: ganado_documentos ganado_documentos_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_documentos
    ADD CONSTRAINT ganado_documentos_ganado_id_foreign FOREIGN KEY (ganado_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: ganado_genealogias ganado_genealogias_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_genealogias
    ADD CONSTRAINT ganado_genealogias_ganado_id_foreign FOREIGN KEY (ganado_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: ganado_genealogias ganado_genealogias_pariente_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_genealogias
    ADD CONSTRAINT ganado_genealogias_pariente_id_foreign FOREIGN KEY (pariente_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: ganado_imagenes ganado_imagenes_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_imagenes
    ADD CONSTRAINT ganado_imagenes_ganado_id_foreign FOREIGN KEY (ganado_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: ganado_logros ganado_logros_ganado_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganado_logros
    ADD CONSTRAINT ganado_logros_ganado_id_foreign FOREIGN KEY (ganado_id) REFERENCES public.ganados(id) ON DELETE CASCADE;


--
-- Name: ganados ganados_categoria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados
    ADD CONSTRAINT ganados_categoria_id_foreign FOREIGN KEY (categoria_id) REFERENCES public.categorias(id) ON DELETE CASCADE;


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
-- Name: ganados ganados_ubicacion_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ganados
    ADD CONSTRAINT ganados_ubicacion_id_foreign FOREIGN KEY (ubicacion_id) REFERENCES public.ubicaciones(id) ON DELETE SET NULL;


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
-- Name: maquinarias maquinarias_ubicacion_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.maquinarias
    ADD CONSTRAINT maquinarias_ubicacion_id_foreign FOREIGN KEY (ubicacion_id) REFERENCES public.ubicaciones(id) ON DELETE SET NULL;


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
-- Name: organico_trazabilidades organico_trazabilidades_organico_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organico_trazabilidades
    ADD CONSTRAINT organico_trazabilidades_organico_id_foreign FOREIGN KEY (organico_id) REFERENCES public.organicos(id) ON DELETE CASCADE;


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
-- Name: organicos organicos_ubicacion_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organicos
    ADD CONSTRAINT organicos_ubicacion_id_foreign FOREIGN KEY (ubicacion_id) REFERENCES public.ubicaciones(id) ON DELETE SET NULL;


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
-- Name: pedido_detalles pedido_detalles_publicacion_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedido_detalles
    ADD CONSTRAINT pedido_detalles_publicacion_id_foreign FOREIGN KEY (publicacion_id) REFERENCES public.publicaciones(id) ON DELETE SET NULL;


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
-- Name: publicaciones publicaciones_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.publicaciones
    ADD CONSTRAINT publicaciones_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


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

\unrestrict wjDOIjasuoUv4hueGepz6hOvOV3ynHO3mgzek0vw2OtzUNFeIjORozwh1uhWfDO

