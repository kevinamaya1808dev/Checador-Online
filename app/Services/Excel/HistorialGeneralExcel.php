<?php

namespace App\Services\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HistorialGeneralExcel
{
    protected Spreadsheet $spreadsheet;

    protected Worksheet $sheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();

        $this->sheet = $this->spreadsheet->getActiveSheet();

        $this->sheet->setTitle('Historial General');
    }

    /**
     * Generar el archivo Excel
     */
    public function generar(
        $asistencias,
        array $resumen,
        array $filtros = []
    ): Spreadsheet {

        $this->crearEncabezado($filtros);

        $this->crearResumen($resumen);

        $this->crearTabla($asistencias);

        $this->aplicarEstilos();

        return $this->spreadsheet;
    }

    /**
     * Encabezado
     */
    private function crearEncabezado(array $filtros): void
{
    /*
    |--------------------------------------------------------------------------
    | Título principal
    |--------------------------------------------------------------------------
    */

    $this->sheet->mergeCells('A1:H1');

    $this->sheet->setCellValue(
        'A1',
        'OLLIN CHECK'
    );

    $this->sheet->mergeCells('A2:H2');

    $this->sheet->setCellValue(
        'A2',
        'Sistema de Control de Asistencia'
    );

    $this->sheet->mergeCells('A3:H3');

    $this->sheet->setCellValue(
        'A3',
        'Reporte General de Asistencias'
    );



    /*
    |--------------------------------------------------------------------------
    | Información del reporte
    |--------------------------------------------------------------------------
    */

    $this->sheet->setCellValue(
        'A5',
        'Generado:'
    );

    $this->sheet->setCellValue(
        'B5',
        now()->format('d/m/Y H:i')
    );



    /*
    |--------------------------------------------------------------------------
    | Filtros aplicados
    |--------------------------------------------------------------------------
    */

    $this->sheet->setCellValue(
        'A6',
        'Búsqueda'
    );

    $this->sheet->setCellValue(
        'B6',
        $filtros['search'] ?? 'Todos'
    );



    $this->sheet->setCellValue(
        'A7',
        'Mes'
    );

    $this->sheet->setCellValue(
        'B7',
        $filtros['mes'] ?? 'Todos'
    );



    $this->sheet->setCellValue(
        'A8',
        'Semana'
    );

    $this->sheet->setCellValue(
        'B8',
        $filtros['semana'] ?? 'Todas'
    );
}

    /**
     * Resumen
     */
    private function crearResumen(array $resumen): void
{
    /*
    |--------------------------------------------------------------------------
    | Título
    |--------------------------------------------------------------------------
    */

    $this->sheet->mergeCells('A10:H10');

    $this->sheet->setCellValue(
        'A10',
        'RESUMEN DEL REPORTE'
    );



    /*
    |--------------------------------------------------------------------------
    | Encabezados
    |--------------------------------------------------------------------------
    */

    $this->sheet->setCellValue('A12', 'Jornadas');
    $this->sheet->setCellValue('B12', 'Horas trabajadas');
    $this->sheet->setCellValue('C12', 'Tiempo en pausa');
    $this->sheet->setCellValue('D12', 'Horas extra');



    /*
    |--------------------------------------------------------------------------
    | Valores
    |--------------------------------------------------------------------------
    */

    $this->sheet->setCellValue(
        'A13',
        $resumen['jornadas']
    );

    $this->sheet->setCellValue(
        'B13',
        $resumen['horas_trabajadas']
    );

    $this->sheet->setCellValue(
        'C13',
        $resumen['tiempo_pausa']
    );

    $this->sheet->setCellValue(
        'D13',
        $resumen['horas_extra']
    );
}

    /**
     * Tabla
     */
    private function crearTabla($asistencias): void
{
    /*
    |--------------------------------------------------------------------------
    | Encabezados
    |--------------------------------------------------------------------------
    */

    $fila = 16;

    $encabezados = [

        'A' => 'Becario',

        'B' => 'Fecha',

        'C' => 'Entrada',

        'D' => 'Salida',

        'E' => 'Pausas',

        'F' => 'Tiempo pausa',

        'G' => 'Tiempo trabajado',

        'H' => 'Horas extra',

    ];

    foreach ($encabezados as $columna => $titulo) {

        $this->sheet->setCellValue(
            $columna.$fila,
            $titulo
        );

    }

    $fila++;



    /*
    |--------------------------------------------------------------------------
    | Datos
    |--------------------------------------------------------------------------
    */

    foreach ($asistencias as $asistencia) {

        $this->sheet->setCellValue(
            'A'.$fila,
            $asistencia->user->name
        );

        $this->sheet->setCellValue(
            'B'.$fila,
            $asistencia->fecha
        );

        $this->sheet->setCellValue(
            'C'.$fila,
            $asistencia->hora_entrada ?? '--'
        );

        $this->sheet->setCellValue(
            'D'.$fila,
            $asistencia->hora_salida ?? '--'
        );

        $this->sheet->setCellValue(
            'E'.$fila,
            $asistencia->pausas->count()
        );

        $this->sheet->setCellValue(
            'F'.$fila,
            $asistencia->tiempoPausas()
        );

        $this->sheet->setCellValue(
            'G'.$fila,
            $asistencia->formatoTiempo(
                $asistencia->tiempoTrabajado()
            )
        );

        $this->sheet->setCellValue(
            'H'.$fila,
            $asistencia->horasExtrasTotalFormato()
        );

        $fila++;

    }
}

    /**
     * Estilos finales
     */
    private function aplicarEstilos(): void
{
    /*
    |--------------------------------------------------------------------------
    | Colores institucionales
    |--------------------------------------------------------------------------
    */

    $azul = '1E3A8A';
    $gris = 'F3F4F6';
    $blanco = 'FFFFFF';

    /*
    |--------------------------------------------------------------------------
    | Títulos principales
    |--------------------------------------------------------------------------
    */

    foreach (['A1', 'A2', 'A3'] as $celda) {

        $this->sheet->getStyle($celda)
            ->getFont()
            ->setBold(true);

        $this->sheet->getStyle($celda)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    }

    $this->sheet->getStyle('A1')->getFont()->setSize(20);
    $this->sheet->getStyle('A2')->getFont()->setSize(14);
    $this->sheet->getStyle('A3')->getFont()->setSize(12);

    $this->sheet->getStyle('A1:H3')
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB($azul);

    $this->sheet->getStyle('A1:H3')
        ->getFont()
        ->getColor()
        ->setARGB($blanco);

    /*
    |--------------------------------------------------------------------------
    | Resumen
    |--------------------------------------------------------------------------
    */

    $this->sheet->getStyle('A10:H10')
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB($azul);

    $this->sheet->getStyle('A10:H10')
        ->getFont()
        ->setBold(true)
        ->getColor()
        ->setARGB($blanco);

    $this->sheet->getStyle('A12:D12')
        ->getFont()
        ->setBold(true);

    $this->sheet->getStyle('A12:D13')
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    $this->sheet->getStyle('A12:D12')
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB($gris);

    /*
    |--------------------------------------------------------------------------
    | Encabezado de tabla
    |--------------------------------------------------------------------------
    */

    $this->sheet->getStyle('A16:H16')
        ->getFont()
        ->setBold(true);

    $this->sheet->getStyle('A16:H16')
        ->getFont()
        ->getColor()
        ->setARGB($blanco);

    $this->sheet->getStyle('A16:H16')
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB($azul);

    /*
    |--------------------------------------------------------------------------
    | Bordes de la tabla
    |--------------------------------------------------------------------------
    */

    $ultimaFila = $this->sheet->getHighestRow();

    $this->sheet->getStyle("A16:H{$ultimaFila}")
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    /*
    |--------------------------------------------------------------------------
    | Centrar contenido
    |--------------------------------------------------------------------------
    */

    $this->sheet->getStyle("A12:H{$ultimaFila}")
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    /*
    |--------------------------------------------------------------------------
    | Autoajuste de columnas
    |--------------------------------------------------------------------------
    */

    foreach (range('A', 'H') as $columna) {

        $this->sheet
            ->getColumnDimension($columna)
            ->setAutoSize(true);

    }

    /*
    |--------------------------------------------------------------------------
    | Congelar encabezado de la tabla
    |--------------------------------------------------------------------------
    */

    $this->sheet->freezePane('A17');

    /*
    |--------------------------------------------------------------------------
    | Autofiltro
    |--------------------------------------------------------------------------
    */

    $this->sheet->setAutoFilter(
        "A16:H{$ultimaFila}"
    );
}

/**
 * Guardar el archivo Excel
 */
public function guardarComo(
    string $ruta,
    $asistencias,
    array $resumen,
    array $filtros = []
): void {

    $spreadsheet = $this->generar(
        $asistencias,
        $resumen,
        $filtros
    );

    $writer = new Xlsx($spreadsheet);

    $writer->save($ruta);

}
}