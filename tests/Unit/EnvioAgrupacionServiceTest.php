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

    public function test_maquinaria_no_se_agrupa_aunque_tenga_mismo_vendedor_y_origen(): void
    {
        $service = new EnvioAgrupacionService;
        $items = new Collection([
            $this->item(1, 'maquinaria', 8, -17.7833000, -63.1821000, 'Finca Norte'),
            $this->item(2, 'maquinaria', 8, -17.7833000, -63.1821000, 'Finca Norte'),
        ]);

        $grupos = $service->agrupar($items);

        $this->assertNotSame($grupos[1]['grupo_envio'], $grupos[2]['grupo_envio']);
    }

    public function test_calcula_distancia_aproximada_entre_dos_coordenadas(): void
    {
        $service = new EnvioAgrupacionService;
        $distancia = $service->distanciaMetros(
            -17.7833000,
            -63.1821000,
            -17.7843000,
            -63.1821000
        );

        $this->assertGreaterThan(100, $distancia);
        $this->assertLessThan(120, $distancia);
    }

    private function item(
        int $id,
        string $tipo,
        int $vendedorId,
        ?float $latitud,
        ?float $longitud,
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
