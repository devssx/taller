<?php

namespace App\Http\Controllers;

use DateTime;
use DateInterval;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
//use PhpOffice\PhpSpreadsheet\IOFactory;


class ExportToExcelController extends Controller
{
    public function expenses(Request $request)
    {
        $start = new DateTime($request->get('start'));
        $end = new DateTime($request->get('start'));
        $end->add(new DateInterval('P7D'));

        $expenseController = new ExpenseController();
        $data = $expenseController->searchWeek($request);
        $gastosSinIva = array();
        $gastosConIva = array();
        $gastosConIvaCred = array();

        // gastos
        foreach ($data['expenses'] as $expense) {
            if ($expense->type == 1) {
                array_push($gastosSinIva, $expense);
            } else if ($expense->type == 2) {
                array_push($gastosConIva, $expense);
            } else if ($expense->type == 3) {
                array_push($gastosConIvaCred, $expense);
            }
        }

        $fondos = 0;
        $gastos = 0;
        foreach ($data['funds'] as $fund) {
            $fondos += $fund->amount;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()->setCreator('Taller Gallegos')->setTitle('Gastos');
        $sheet->getStyle('A')->getFont()->setBold(true);

        // column sizes
        $sheet->getColumnDimension('A')->setWidth(160, 'px');
        $sheet->getColumnDimension('B')->setWidth(160, 'px');
        $sheet->getColumnDimension('D')->setWidth(256, 'px');

        $sheet->setCellValue('A1', 'Taller')->setCellValue('B1', 'Teran Teran');
        $sheet->setCellValue('A2', 'Semana')->setCellValue('B2', date_format($start, 'd-m-Y') . ' al ' . date_format($end, 'd-m-Y'));


        // table 1 (Gatos sin iva)
        $sheet->getStyle('D1:G2')->getFont()->setBold(true);
        $sheet->getStyle('D1:G1')->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle('D1:G1')->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue('D1', 'GASTOS NO FACTURADOS');
        $sheet->setCellValue('D2', 'CONCEPTO')->setCellValue('G2', 'TOTAL');

        $rowIndex = 3;
        foreach ($gastosSinIva as $expense) {
            $sheet->setCellValue('D' . $rowIndex, $expense->concept)->setCellValue('G' . $rowIndex, $expense->total);
            $gastos += $expense->total;
            $rowIndex++;
        }

        $rowIndex += 2;
        $row1 = $rowIndex;
        $row2 = $rowIndex + 1;
        $sheet->getStyle("D$row1:G$row2")->getFont()->setBold(true);
        $sheet->getStyle("D$row1:G$row1")->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle("D$row1:G$row1")->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue("D$row1", 'GASTOS EN EFECTIVO FACTURADOS');
        $sheet->setCellValue("D$row2", 'CONCEPTO')->setCellValue("E$row2", 'IMPORTE')->setCellValue("F$row2", 'IVA')->setCellValue("G$row2", 'TOTAL');
        $rowIndex += 2;
        foreach ($gastosConIva as $expense) {
            $sheet->setCellValue('D' . $rowIndex, $expense->concept)->setCellValue('E' . $rowIndex, $expense->amount)->setCellValue('F' . $rowIndex, $expense->iva)->setCellValue('G' . $rowIndex, $expense->total);
            $gastos += $expense->amount;
            $rowIndex++;
        }

        $rowIndex += 2;
        $row1 = $rowIndex;
        $row2 = $rowIndex + 1;
        $sheet->getStyle("D$row1:G$row2")->getFont()->setBold(true);
        $sheet->getStyle("D$row1:G$row1")->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle("D$row1:G$row1")->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue("D$row1", 'GASTOS A CREDITO FACTURADOS');
        $sheet->setCellValue("D$row2", 'CONCEPTO')->setCellValue("E$row2", 'IMPORTE')->setCellValue("F$row2", 'IVA')->setCellValue("G$row2", 'TOTAL');
        $rowIndex += 2;
        foreach ($gastosConIvaCred as $expense) {
            $sheet->setCellValue('D' . $rowIndex, $expense->concept)->setCellValue('E' . $rowIndex, $expense->amount)->setCellValue('F' . $rowIndex, $expense->iva)->setCellValue('G' . $rowIndex, $expense->total);
            $gastos += $expense->amount;
            $rowIndex++;
        }


        $rowIndex += 2;
        $row1 = $rowIndex;
        $row2 = $rowIndex + 1;
        $sheet->getStyle("D$row1:G$row2")->getFont()->setBold(true);
        $sheet->getStyle("D$row1:G$row1")->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle("D$row1:G$row1")->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue("D$row1", 'EMPLEADOS');
        $sheet->setCellValue("D$row2", 'NOMINA TOTAL')->setCellValue("E$row2", 'SEMANA')->setCellValue("F$row2", 'INICIO')->setCellValue("G$row2", 'FIN');
        $rowIndex += 2;
        foreach ($gastosConIvaCred as $expense) {
            $sheet->setCellValue('D' . $rowIndex, $expense->concept)->setCellValue('E' . $rowIndex, $expense->amount)->setCellValue('F' . $rowIndex, $expense->iva)->setCellValue('G' . $rowIndex, $expense->total);
            $gastos += $expense->amount;
            $rowIndex++;
        }

        $sheet->getStyle('A5:B7')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A5:B7')->getFill()->getStartColor()->setARGB('FFF5F7FA');
        $sheet->setCellValue('A5', 'Fondo')->setCellValue('B5', $fondos);
        $sheet->setCellValue('A6', 'Gasto')->setCellValue('B6', $gastos);
        $sheet->setCellValue('A7', 'Resto')->setCellValue('B7', $fondos - $gastos);

        $sheet->getStyle('A10:B13')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A10:B13')->getFill()->getStartColor()->setARGB('FFF5F7FA');
        $sheet->setCellValue('A10', 'Ingresos sin Iva')->setCellValue('B10', 0);
        $sheet->setCellValue('A11', 'Gastos sin Iva')->setCellValue('B11', 0);
        $sheet->setCellValue('A12', 'Utilidad sin Iva')->setCellValue('B12', 0);
        $sheet->setCellValue('A13', 'Ingresos Pend. Créditos')->setCellValue('B13', 0);

        // // carga nomina de la semana
        // $this.setPayroll($data->payroll, newDate);

        // // Ingresos
        // $this.setIncome($data->income);


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
