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

    public function test_flujo_delivery_avanza_en_orden_para_todos_los_productos(): void
    {
        $service = new TransporteAccesoService();

        foreach (['ganado', 'maquinaria', 'organico'] as $productType) {
            $detalle = new PedidoDetalle([
                'product_type' => $productType,
                'estado_solicitud' => 'aceptada',
                'estado_transporte' => 'aceptado',
            ]);

            $this->assertNull($service->siguienteEstado($detalle));

            $detalle->estado_transporte = 'preparando';
            $this->assertSame('en_camino_entrega', $service->siguienteEstado($detalle));

            $detalle->estado_transporte = 'en_camino_entrega';
            $this->assertSame('esperando_confirmacion', $service->siguienteEstado($detalle));
        }
    }
}
