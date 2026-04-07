<?php

namespace App\Exports;

use Illuminate\Http\Response;

class VendedoresExport
{
    protected $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function export()
    {
        $fechaArchivo = \Carbon\Carbon::now()->format('Y-m-d_His');
        $nombreArchivo = "reporte_vendedores_{$fechaArchivo}.xlsx";

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
            '#',
            'Vendedor',
            'Email',
            'Total Productos',
            'Animales',
            'Maquinaria',
            'Orgánicos',
            'Ventas Totales (Bs.)'
        ], ';');

        // Datos
        foreach ($this->datos as $index => $vendedor) {
            fputcsv($output, [
                $index + 1,
                $vendedor['nombre'],
                $vendedor['email'],
                $vendedor['total_productos'],
                $vendedor['total_ganados'],
                $vendedor['total_maquinarias'],
                $vendedor['total_organicos'],
                number_format($vendedor['total_ventas'], 2, '.', ''),
            ], ';');
        }

        rewind($output);
        $contenido = stream_get_contents($output);
        fclose($output);

        return $contenido;
    }
}

