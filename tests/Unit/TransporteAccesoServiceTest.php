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
}
