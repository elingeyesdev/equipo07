<?php

namespace Tests\Unit;

use App\Services\EnvioAgrupacionService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class EnvioAgrupacionServiceTest extends TestCase
{
    public function test_agrupa_organicos_del_mismo_vendedor_dentro_de_500_metros(): void
    {
        $service = new EnvioAgrupacionService;
        $items = new Collection([
            $this->item(1, 'organico', 8, -17.7833000, -63.1821000, 'Finca Norte'),
            $this->item(2, 'organico', 8, -17.7860000, -63.1821000, 'Otro sector'),
        ]);

        $grupos = $service->agrupar($items);

        $this->assertSame($grupos[1]['grupo_envio'], $grupos[2]['grupo_envio']);
    }

    public function test_separa_productos_que_superan_el_radio_permitido(): void
    {
        $service = new EnvioAgrupacionService;
        $items = new Collection([
            $this->item(1, 'organico', 8, -17.7833000, -63.1821000, 'Finca Norte'),
            $this->item(2, 'organico', 8, -17.7933000, -63.1821000, 'Finca Sur'),
        ]);

        $grupos = $service->agrupar($items);

        $this->assertNotSame($grupos[1]['grupo_envio'], $grupos[2]['grupo_envio']);
    }

    public function test_no_mezcla_organicos_con_ganado_aunque_compartan_origen(): void
    {
        $service = new EnvioAgrupacionService;
        $items = new Collection([
            $this->item(1, 'organico', 8, -17.7833000, -63.1821000, 'Finca Norte'),
            $this->item(2, 'ganado', 8, -17.7833000, -63.1821000, 'Finca Norte'),
        ]);

        $grupos = $service->agrupar($items);

        $this->assertNotSame($grupos[1]['grupo_envio'], $grupos[2]['grupo_envio']);
    }

    public function test_sin_coordenadas_solo_agrupa_la_misma_direccion_normalizada(): void
    {
        $service = new EnvioAgrupacionService;
        $items = new Collection([
            $this->item(1, 'ganado', 3, null, null, 'Granja El Sol, Warnes'),
            $this->item(2, 'ganado', 3, null, null, ' granja el sol - warnes '),
        ]);

        $grupos = $service->agrupar($items);

        $this->assertSame($grupos[1]['grupo_envio'], $grupos[2]['grupo_envio']);
    }

    public function test_no_agrupa_productos_de_vendedores_diferentes(): void
    {
        $service = new EnvioAgrupacionService;
        $items = new Collection([
            $this->item(1, 'organico', 8, -17.7833000, -63.1821000, 'Finca Norte'),
            $this->item(2, 'organico', 9, -17.7833000, -63.1821000, 'Finca Norte'),
        ]);

        $grupos = $service->agrupar($items);

        $this->assertNotSame($grupos[1]['grupo_envio'], $grupos[2]['grupo_envio']);
    }

    public function test_maquinaria_siempre_viaja_en_grupos_independientes(): void
    {
        $service = new EnvioAgrupacionService;
        $items = new Collection([
            $this->item(1, 'maquinaria', 8, -17.7833000, -63.1821000, 'Deposito Central'),
            $this->item(2, 'maquinaria', 8, -17.7833000, -63.1821000, 'Deposito Central'),
        ]);

        $grupos = $service->agrupar($items);

        $this->assertNotSame($grupos[1]['grupo_envio'], $grupos[2]['grupo_envio']);
    }

    public function test_distancia_metros_calcula_cero_para_el_mismo_punto(): void
    {
        $service = new EnvioAgrupacionService;

        $this->assertSame(0.0, $service->distanciaMetros(-17.7833, -63.1821, -17.7833, -63.1821));
    }

    public function test_resultado_conserva_direccion_y_coordenadas_de_origen(): void
    {
        $service = new EnvioAgrupacionService;
        $items = new Collection([
            $this->item(1, 'organico', 8, '-17.7833000', '-63.1821000', 'Finca Norte'),
        ]);

        $grupos = $service->agrupar($items);

        $this->assertSame('Finca Norte', $grupos[1]['origen_direccion']);
        $this->assertSame(-17.7833000, $grupos[1]['origen_latitud']);
        $this->assertSame(-63.1821000, $grupos[1]['origen_longitud']);
    }

    private function item(
        int $id,
        string $tipo,
        int $vendedorId,
        mixed $latitud,
        mixed $longitud,
        string $direccion
    ): object {
        $product = (object) [
            'user_id' => $vendedorId,
            'origen' => $direccion,
            'ubicacion' => $direccion,
            'latitud_origen' => $latitud,
            'longitud_origen' => $longitud,
            'latitud' => $latitud,
            'longitud' => $longitud,
        ];

        return (object) [
            'id' => $id,
            'product_type' => $tipo,
            'product' => $product,
        ];
    }
}
