<?php

namespace Tests\Unit;

use App\Models\PedidoDetalle;
use App\Services\TransporteAccesoService;
use PHPUnit\Framework\TestCase;

class TransporteAccesoServiceTest extends TestCase
{
    public function test_normaliza_codigos_con_separadores_y_minusculas(): void
    {
        $this->assertSame(
            'ABCD1234EFGH',
            TransporteAccesoService::normalizarCodigo(' abcd-1234 efgh ')
        );
    }

    public function test_el_hash_no_depende_del_formato_del_codigo(): void
    {
        $this->assertSame(
            TransporteAccesoService::hashCodigo('ABCD-1234-EFGH'),
            TransporteAccesoService::hashCodigo('abcd 1234 efgh')
        );
    }

    public function test_flujo_organico_avanza_en_orden(): void
    {
        $service = new TransporteAccesoService();
        $detalle = new PedidoDetalle([
            'product_type' => 'organico',
            'estado_solicitud' => 'aceptada',
            'estado_transporte' => 'aceptado',
        ]);

        $this->assertNull($service->siguienteEstado($detalle));

        $detalle->estado_transporte = 'preparando';
        $this->assertSame('en_camino_entrega', $service->siguienteEstado($detalle));

        $detalle->estado_transporte = 'en_camino_entrega';
        $this->assertSame('esperando_confirmacion', $service->siguienteEstado($detalle));
    }

    public function test_flujo_maquinaria_usa_recogida_entrega_y_retorno(): void
    {
        $service = new TransporteAccesoService();
        $detalle = new PedidoDetalle([
            'product_type' => 'maquinaria',
            'estado_solicitud' => 'aceptada',
            'estado_transporte' => 'asignado',
        ]);

        $this->assertSame('en_camino_recogida', $service->siguienteEstado($detalle));

        $detalle->estado_transporte = 'en_camino_recogida';
        $this->assertSame('producto_recogido', $service->siguienteEstado($detalle));

        $detalle->estado_transporte = 'producto_recogido';
        $this->assertSame('en_camino_entrega', $service->siguienteEstado($detalle));

        $detalle->estado_transporte = 'en_camino_entrega';
        $this->assertSame('llego_destino', $service->siguienteEstado($detalle));

        $detalle->estado_transporte = 'llego_destino';
        $this->assertSame('esperando_confirmacion', $service->siguienteEstado($detalle));

        $detalle->estado_transporte = 'entregado';
        $this->assertSame('en_camino_retorno', $service->siguienteEstado($detalle));

        $detalle->estado_transporte = 'en_camino_retorno';
        $this->assertSame('devuelto_vendedor', $service->siguienteEstado($detalle));
    }

    public function test_estados_para_organico_no_incluye_estados_de_retorno(): void
    {
        $service = new TransporteAccesoService();
        $detalle = new PedidoDetalle([
            'product_type' => 'organico',
            'estado_solicitud' => 'aceptada',
        ]);

        $this->assertSame(TransporteAccesoService::ESTADOS_ORGANICO, $service->estadosPara($detalle));
        $this->assertArrayNotHasKey('en_camino_retorno', $service->estadosPara($detalle));
    }

    public function test_estados_para_maquinaria_usa_catalogo_completo_de_transporte(): void
    {
        $service = new TransporteAccesoService();
        $detalle = new PedidoDetalle([
            'product_type' => 'maquinaria',
            'estado_solicitud' => 'aceptada',
        ]);

        $this->assertSame(PedidoDetalle::transporteEstados(), $service->estadosPara($detalle));
        $this->assertArrayHasKey('en_camino_retorno', $service->estadosPara($detalle));
    }

    public function test_gps_organico_solo_se_activa_en_estados_operativos(): void
    {
        $service = new TransporteAccesoService();
        $detalle = new PedidoDetalle([
            'product_type' => 'organico',
            'estado_solicitud' => 'aceptada',
            'estado_transporte' => 'preparando',
        ]);

        $this->assertTrue($service->puedeActivarGps($detalle));

        $detalle->estado_transporte = 'entregado';
        $this->assertFalse($service->puedeActivarGps($detalle));
    }

    public function test_gps_maquinaria_se_desactiva_en_retorno_finalizado(): void
    {
        $service = new TransporteAccesoService();
        $detalle = new PedidoDetalle([
            'product_type' => 'maquinaria',
            'estado_solicitud' => 'aceptada',
            'estado_transporte' => 'en_camino_recogida',
        ]);

        $this->assertTrue($service->puedeActivarGps($detalle));

        $detalle->estado_transporte = 'devuelto_vendedor';
        $this->assertFalse($service->puedeActivarGps($detalle));
    }

    public function test_tipo_recorrido_distingue_entrega_y_devolucion(): void
    {
        $service = new TransporteAccesoService();
        $detalle = new PedidoDetalle([
            'product_type' => 'maquinaria',
            'estado_solicitud' => 'aceptada',
            'estado_transporte' => 'en_camino_entrega',
        ]);

        $this->assertSame('entrega', $service->tipoRecorrido($detalle));

        $detalle->estado_transporte = 'en_camino_retorno';
        $this->assertSame('devolucion', $service->tipoRecorrido($detalle));
    }
}
