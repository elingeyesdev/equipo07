<?php

namespace App\Exports;

use Illuminate\Http\Response;

class VentasExport
{
    protected $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function export()
    {
        $fechaArchivo = \Carbon\Carbon::now()->format('Y-m-d_His');
        $nombreArchivo = "reporte_ventas_{$fechaArchivo}.xlsx";

        // Crear contenido CSV (compatible con Excel)
        $contenido = $this->generarCSV();

        return response($contenido, 200)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', "attachment; filename=\"{$nombreArchivo}\"")
            ->header('Cache-Control', 'max-age=0');
    }

    protected function generarCSV()
    {
        $output = fopen('php://temp', 'r+');
        
        // BOM para UTF-8 (Excel lo necesita)
        fwrite($output, "\xEF\xBB\xBF");
        
        // Encabezados
        fputcsv($output, [
            'Categoría',
            'Total Pedidos',
            'Cantidad Vendida',
            'Ingresos Totales (Bs.)',
            'Precio Promedio (Bs.)'
        ], ';');

        // Datos
        foreach ($this->datos as $venta) {
            $categoriaNombre = [
                'ganado' => 'Animales',
                'maquinaria' => 'Maquinaria',
                'organico' => 'Orgánicos',
            ][$venta->categoria] ?? ucfirst($venta->categoria);

            fputcsv($output, [
                $categoriaNombre,
                $venta->total_pedidos,
                $venta->total_cantidad,
                number_format($venta->ingresos_totales, 2, '.', ''),
                number_format($venta->precio_promedio, 2, '.', ''),
            ], ';');
        }

        rewind($output);
        $contenido = stream_get_contents($output);
        fclose($output);

        return $contenido;
    }
}
