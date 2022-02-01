<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
//use PhpOffice\PhpSpreadsheet\IOFactory;


class ExportToExcelController extends Controller
{
    public function index()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()->setCreator('Taller Gallegos')->setTitle('Gastos');


        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            // 'alignment' => [
            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            // ],
            // 'borders' => [
            //     'top' => [
            //         'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            //     ],
            // ],
            // 'fill' => [
            //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            //     'rotation' => 90,
            //     'startColor' => [
            //         'argb' => 'FFA0A0A0',
            //     ],
            //     'endColor' => [
            //         'argb' => 'FFFFFFFF',
            //     ],
            // ],
        ];
        //$sheet->getStyle('A')->applyFromArray($styleArray);


        $sheet->getStyle('A')->getFont()->setBold(true);

        // column sizes
        $sheet->getColumnDimension('A')->setWidth(160, 'px');
        $sheet->getColumnDimension('B')->setWidth(120, 'px');
        $sheet->getColumnDimension('D')->setWidth(256, 'px');

        

        $sheet->setCellValue('A1', 'Taller')->setCellValue('B1', 'Teran Teran');
        $sheet->setCellValue('A2', 'Semana')->setCellValue('B2', 'xxxx a xxxx');
        $sheet->getStyle('B2')->getFont()->getColor()->setARGB(Color::COLOR_RED);

        $sheet->getStyle('A5:B7')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A5:B7')->getFill()->getStartColor()->setARGB('FFF5F7FA');
        $sheet->setCellValue('A5', 'Fondo')->setCellValue('B5', 0);
        $sheet->setCellValue('A6', 'Gasto')->setCellValue('B6', 0);
        $sheet->setCellValue('A7', 'Resto')->setCellValue('B7', 0);

        $sheet->getStyle('A10:B13')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A10:B13')->getFill()->getStartColor()->setARGB('FFF5F7FA');
        $sheet->setCellValue('A10', 'Ingresos sin Iva')->setCellValue('B10', 0);
        $sheet->setCellValue('A11', 'Gastos sin Iva')->setCellValue('B11', 0);
        $sheet->setCellValue('A12', 'Utilidad sin Iva')->setCellValue('B12', 0);
        $sheet->setCellValue('A13', 'Ingresos Pend. Créditos')->setCellValue('B13', 0);

        $sheet->getStyle('D5:G6')->getFont()->setBold(true);
        $sheet->getStyle('D5:G5')->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        //$sheet->getStyle('D5:G5')->getFill()->getStartColor()->setARGB('FFF5F7FA');
        $sheet->getStyle('D5:G5')->getFont()->getColor()->setARGB(Color::COLOR_BLUE);
        
        $sheet->setCellValue('D5', 'GASTOS NO FACTURADOS');
        $sheet->setCellValue('D6', 'CONCEPTO')->setCellValue('G6', 'TOTAL');
        $sheet->setCellValue('D7', 'Compra de pizzas')->setCellValue('G7', 480);

        $this->Download($spreadsheet, 'GASTOS');
    }

    private function Download(Spreadsheet $spreadsheet, $name)
    {
        // create folder if not exists
        if (!file_exists('excel'))
            mkdir('excel', 0777, true);

        $filename = "excel/$name-" . date('Y-m-d') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
        }
    }
}
