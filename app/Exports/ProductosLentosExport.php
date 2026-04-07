<?php

namespace App\Exports;

use Illuminate\Http\Response;

class ProductosLentosExport
{
    protected $datos;

    public function __construct($datos)
    {
        // $datos va a ser la colección de productos lentos
        $this->datos = $datos;
    }

    public function export()
    {
        $fechaArchivo = \Carbon\Carbon::now()->format('Y-m-d_His');
        $nombreArchivo = "reporte_productos_lentos_{$fechaArchivo}.xlsx";

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
            'Producto',
            'Vendedor',
            'Precio (Bs.)',
            'Total vendido',
            'Ingresos (Bs.)',
            'Última venta',
        ], ';');

        // Datos
        foreach ($this->datos as $p) {
            fputcsv($output, [
                $p->categoria,
                $p->producto,
                $p->vendedor,
                number_format($p->precio, 2, '.', ''),
                $p->total_vendido,
                number_format($p->ingresos, 2, '.', ''),
                $p->ultima_venta ? \Carbon\Carbon::parse($p->ultima_venta)->format('d/m/Y') : 'Sin ventas',
            ], ';');
        }

        rewind($output);
        $contenido = stream_get_contents($output);
        fclose($output);

        return $contenido;
    }
}
