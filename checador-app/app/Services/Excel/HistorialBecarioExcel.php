<?php

namespace App\Services\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class HistorialBecarioExcel
{

    protected Spreadsheet $spreadsheet;

    protected Worksheet $sheet;


    public function __construct()
    {

        $this->spreadsheet = new Spreadsheet();

        $this->sheet = 
            $this->spreadsheet->getActiveSheet();

        $this->sheet->setTitle(
            'Reporte Becario'
        );

    }


    public function generar(
        $asistencias,
        array $resumen,
        string $nombreBecario,
        array $filtros = []
    ): Spreadsheet {


        $this->crearEncabezado(
            $nombreBecario,
            $filtros
        );


        $this->crearResumen(
            $resumen
        );


        $this->crearTabla(
            $asistencias
        );


        $this->aplicarEstilos();


        return $this->spreadsheet;

    }


    /*
    |--------------------------------------------------------------------------
    | Encabezado
    |--------------------------------------------------------------------------
    */

    private function crearEncabezado(
        string $nombreBecario,
        array $filtros
    ): void {


        $this->sheet->mergeCells('A1:G1');

        $this->sheet->setCellValue(
            'A1',
            'OLLIN CHECK'
        );


        $this->sheet->mergeCells('A2:G2');

        $this->sheet->setCellValue(
            'A2',
            'Sistema de Control de Asistencia'
        );


        $this->sheet->mergeCells('A3:G3');

        $this->sheet->setCellValue(
            'A3',
            'Reporte Individual de Becario'
        );


        $this->sheet->setCellValue(
            'A5',
            'Becario'
        );


        $this->sheet->setCellValue(
            'B5',
            $nombreBecario
        );


        $this->sheet->setCellValue(
            'A6',
            'Generado'
        );


        $this->sheet->setCellValue(
            'B6',
            now()->format('d/m/Y H:i')
        );


        $this->sheet->setCellValue(
            'A7',
            'Desde'
        );


        $this->sheet->setCellValue(
            'B7',
            $filtros['desde'] ?? 'Inicio'
        );


        $this->sheet->setCellValue(
            'A8',
            'Hasta'
        );


        $this->sheet->setCellValue(
            'B8',
            $filtros['hasta'] ?? 'Actual'
        );

    }



    /*
    |--------------------------------------------------------------------------
    | Resumen
    |--------------------------------------------------------------------------
    */

    private function crearResumen(
        array $resumen
    ): void {


        $this->sheet->mergeCells('A10:G10');


        $this->sheet->setCellValue(
            'A10',
            'RESUMEN DEL REPORTE'
        );


        $datos = [

            'Jornadas',
            'Horas trabajadas',
            'Tiempo pausa',
            'Horas extra'

        ];


        foreach($datos as $index=>$dato){

            $columna = chr(
                65+$index
            );


            $this->sheet->setCellValue(
                $columna.'12',
                $dato
            );

        }



        $valores=[

            $resumen['jornadas'],

            $resumen['horas_trabajadas'],

            $resumen['tiempo_pausa'],

            $resumen['horas_extra']

        ];


        foreach($valores as $index=>$valor){

            $columna = chr(
                65+$index
            );


            $this->sheet->setCellValue(
                $columna.'13',
                $valor
            );

        }

    }



    /*
    |--------------------------------------------------------------------------
    | Tabla
    |--------------------------------------------------------------------------
    */

    private function crearTabla($asistencias): void
    {


        $fila = 16;


        $encabezados=[

            'Fecha',

            'Entrada',

            'Salida',

            'Pausas',

            'Tiempo pausa',

            'Tiempo trabajado',

            'Horas extra'

        ];


        foreach($encabezados as $index=>$titulo){

            $columna = chr(
                65+$index
            );


            $this->sheet->setCellValue(
                $columna.$fila,
                $titulo
            );

        }


        $fila++;


        foreach($asistencias as $asistencia){


            $datos=[

                Carbon::parse(
                    $asistencia->fecha
                )->format('d/m/Y'),


                $asistencia->hora_entrada ?? '--',


                $asistencia->hora_salida ?? '--',


                $asistencia->pausas->count(),


                $asistencia->tiempoPausas(),


                $asistencia->formatoTiempo(
                    $asistencia->tiempoTrabajado()
                ),


                $asistencia->horasExtrasTotalFormato()

            ];



            foreach($datos as $index=>$dato){


                $columna = chr(
                    65+$index
                );


                $this->sheet->setCellValue(
                    $columna.$fila,
                    $dato
                );

            }


            $fila++;

        }


    }


    private function aplicarEstilos(): void
    {

        $azul='1E3A8A';

        $blanco='FFFFFF';


        foreach(['A1','A2','A3']){


            $this->sheet
                ->getStyle($_)
                ->getFont()
                ->setBold(true);


        }


        $this->sheet
            ->getStyle('A1:G3')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($azul);



        $this->sheet
            ->getStyle('A1:G3')
            ->getFont()
            ->getColor()
            ->setARGB($blanco);



        $this->sheet
            ->getStyle('A16:G16')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($azul);



        $this->sheet
            ->getStyle('A16:G16')
            ->getFont()
            ->setBold(true);



        $ultimaFila =
            $this->sheet->getHighestRow();



        $this->sheet
            ->getStyle("A16:G{$ultimaFila}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                Border::BORDER_THIN
            );



        foreach(range('A','G') as $columna){

            $this->sheet
                ->getColumnDimension($columna)
                ->setAutoSize(true);

        }


        $this->sheet->freezePane(
            'A17'
        );


    }



    public function guardarComo(
        string $ruta,
        $asistencias,
        array $resumen,
        string $nombreBecario,
        array $filtros=[]
    ): void {


        $spreadsheet =
            $this->generar(
                $asistencias,
                $resumen,
                $nombreBecario,
                $filtros
            );


        $writer = new Xlsx(
            $spreadsheet
        );


        $writer->save($ruta);

    }

}