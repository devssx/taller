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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()->setCreator('Taller Gallegos')->setTitle('Gastos');
        $sheet->getStyle('A')->getFont()->setBold(true);

        // column sizes
        $sheet->getColumnDimension('A')->setWidth(160, 'px');
        $sheet->getColumnDimension('B')->setWidth(160, 'px');
        $sheet->getColumnDimension('D')->setWidth(256, 'px');

        $sheet->setCellValue('A1', 'Taller')->setCellValue('B1', $request->get('taller'));
        $sheet->setCellValue('A2', 'Semana')->setCellValue('B2', date_format($start, 'd-m-Y') . ' al ' . date_format($end, 'd-m-Y'));


        // table 1 (Gatos sin iva)
        $sheet->getStyle('D1:H2')->getFont()->setBold(true);
        $sheet->getStyle('D1:H1')->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle('D1:H1')->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue('D1', 'GASTOS NO FACTURADOS');
        $sheet->setCellValue('D2', 'CONCEPTO')->setCellValue('H2', 'TOTAL');

        $rowIndex = 3;
        foreach ($request->get('table1') as $expense) {
            $sheet->setCellValue('D' . $rowIndex, $expense['concept'])->setCellValue('H' . $rowIndex, $expense['total']);
            $rowIndex++;
        }

        $rowIndex += 2;
        $row1 = $rowIndex;
        $row2 = $rowIndex + 1;
        $sheet->getStyle("D$row1:H$row2")->getFont()->setBold(true);
        $sheet->getStyle("D$row1:H$row1")->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle("D$row1:H$row1")->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue("D$row1", 'GASTOS EN EFECTIVO FACTURADOS');
        $sheet->setCellValue("D$row2", 'CONCEPTO')->setCellValue("E$row2", 'FACTURA')->setCellValue("F$row2", 'IMPORTE')->setCellValue("G$row2", 'IVA')->setCellValue("H$row2", 'TOTAL');
        $rowIndex += 2;
        foreach ($request->get('table2') as $expense) {
            $sheet->setCellValue('D' . $rowIndex, $expense['concept'])->setCellValue('E' . $rowIndex, $expense['bill'])->setCellValue('F' . $rowIndex, $expense['amount'])->setCellValue('G' . $rowIndex, $expense['iva'])->setCellValue('H' . $rowIndex, $expense['total']);
            $rowIndex++;
        }

        $rowIndex += 2;
        $row1 = $rowIndex;
        $row2 = $rowIndex + 1;
        $sheet->getStyle("D$row1:H$row2")->getFont()->setBold(true);
        $sheet->getStyle("D$row1:H$row1")->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle("D$row1:H$row1")->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue("D$row1", 'GASTOS A CREDITO FACTURADOS');
        $sheet->setCellValue("D$row2", 'CONCEPTO')->setCellValue("E$row2", 'FACTURA')->setCellValue("F$row2", 'IMPORTE')->setCellValue("G$row2", 'IVA')->setCellValue("H$row2", 'TOTAL');
        $rowIndex += 2;

        foreach ($request->get('table3') as $expense) {
            $sheet->setCellValue('D' . $rowIndex, $expense['concept'])->setCellValue('E' . $rowIndex, $expense['bill'])->setCellValue('F' . $rowIndex, $expense['amount'])->setCellValue('G' . $rowIndex, $expense['iva'])->setCellValue('H' . $rowIndex, $expense['total']);
            $rowIndex++;
        }

        $rowIndex += 2;
        $row1 = $rowIndex;
        $row2 = $rowIndex + 1;
        $sheet->getStyle("D$row1:H$row2")->getFont()->setBold(true);
        $sheet->getStyle("D$row1:H$row1")->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle("D$row1:H$row1")->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue("D$row1", 'EMPLEADOS');
        $sheet->setCellValue("D$row2", 'NOMINA TOTAL')->setCellValue("E$row2", 'SEMANA')->setCellValue("F$row2", 'INICIO')->setCellValue("G$row2", 'FIN')->setCellValue("H$row2", 'TOTAL');
        $rowIndex += 2;

        foreach ($request->get('dataPayroll') as $expense) {
            $sheet->setCellValue('D' . $rowIndex, $expense['concept'])->setCellValue('E' . $rowIndex, $expense['week'])->setCellValue('F' . $rowIndex, $expense['start'])->setCellValue('G' . $rowIndex, $expense['end'])->setCellValue('H' . $rowIndex, $expense['total']);
            $rowIndex++;
        }

        $funds = doubleval($request->get('funds'));
        $ingresosSinIva = doubleval($request->get('ingresosSinIva'));
        $totalCreditos = doubleval($request->get('totalCreditos'));
        $totalSinIva = doubleval($request->get('totalSinIva'));
        $totalPayroll = doubleval($request->get('totalPayroll'));

        $sheet->getStyle('A5:B7')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A5:B7')->getFill()->getStartColor()->setARGB('FFF5F7FA');
        $sheet->setCellValue('A5', 'Fondo')->setCellValue('B5', $funds);
        $sheet->setCellValue('A6', 'Gasto')->setCellValue('B6', $totalSinIva - $totalPayroll);
        $sheet->setCellValue('A7', 'Resto')->setCellValue('B7', $funds - ($totalSinIva - $totalPayroll));

        $sheet->getStyle('A10:B13')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A10:B13')->getFill()->getStartColor()->setARGB('FFF5F7FA');
        $sheet->setCellValue('A10', 'Ingresos sin Iva')->setCellValue('B10', $ingresosSinIva);
        $sheet->setCellValue('A11', 'Gastos sin Iva')->setCellValue('B11', $totalSinIva);
        $sheet->setCellValue('A12', 'Utilidad sin Iva')->setCellValue('B12', $ingresosSinIva - $totalSinIva);
        $sheet->setCellValue('A13', 'Ingresos Pend. Créditos')->setCellValue('B13', $totalCreditos);

        $filename = $this->createFile($spreadsheet, 'GASTOS');
        return response()->json(['file' => $filename,]);
    }

    public function income(Request $request)
    {
        $start = new DateTime($request->get('start'));
        $end = new DateTime($request->get('start'));
        $end->add(new DateInterval('P7D'));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()->setCreator('Taller Gallegos')->setTitle('Gastos');
        $sheet->getStyle('A')->getFont()->setBold(true);

        // column sizes
        $sheet->getColumnDimension('A')->setWidth(160, 'px');
        $sheet->getColumnDimension('B')->setWidth(160, 'px');

        $sheet->setCellValue('A1', 'Taller')->setCellValue('B1', $request->get('taller'));
        $sheet->setCellValue('A2', 'Semana')->setCellValue('B2', date_format($start, 'd-m-Y') . ' al ' . date_format($end, 'd-m-Y'));


        // table 1 (Gatos sin iva)
        $sheet->getStyle('D1:J2')->getFont()->setBold(true);
        $sheet->getStyle('D1:J1')->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle('D1:J1')->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue('D1', 'INGRESOS EFECTIVO');
        $sheet->setCellValue('D2', 'RECIBO')->setCellValue('J2', 'TOTAL');

        $rowIndex = 3;
        foreach ($request->get('dataEfectivo') as $income) {
            $sheet->setCellValue('D' . $rowIndex, $income['folio'])->setCellValue('J' . $rowIndex, $income['total']);
            $rowIndex++;
        }

        $rowIndex += 2;
        $row1 = $rowIndex;
        $row2 = $rowIndex + 1;
        $sheet->getStyle("D$row1:J$row2")->getFont()->setBold(true);
        $sheet->getStyle("D$row1:J$row1")->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle("D$row1:J$row1")->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue("D$row1", 'INGRESOS FACTURADOS EFECTIVO');
        $sheet->setCellValue("D$row2", 'RECIBO')->setCellValue("E$row2", 'FACTURA')->setCellValue("F$row2", 'EFECTIVO')->setCellValue("G$row2", 'IMP. SIN IVA')->setCellValue("H$row2", 'IVA')->setCellValue("J$row2", 'TOTAL');
        $rowIndex += 2;
        foreach ($request->get('dataIvaEfectivo') as $income) {
            $sheet->setCellValue('D' . $rowIndex, $income['folio'])->setCellValue('E' . $rowIndex, $income['factura'])->setCellValue('F' . $rowIndex, $income['efectivo'])->setCellValue('G' . $rowIndex, $income['importe'])->setCellValue('H' . $rowIndex, $income['iva'])->setCellValue('J' . $rowIndex, $income['total']);
            $rowIndex++;
        }

        $rowIndex += 2;
        $row1 = $rowIndex;
        $row2 = $rowIndex + 1;
        $sheet->getStyle("D$row1:J$row2")->getFont()->setBold(true);
        $sheet->getStyle("D$row1:J$row1")->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle("D$row1:J$row1")->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue("D$row1", 'Ingresos facturados T. Débito / T. Crédito / Transferencia / Cheque');
        $sheet->setCellValue("D$row2", 'RECIBO')->setCellValue("E$row2", 'FACTURA')->setCellValue("F$row2", 'FECHA PAGO')->setCellValue("G$row2", 'IMP. SIN IVA')->setCellValue("H$row2", 'IVA')->setCellValue("J$row2", 'TOTAL');
        $rowIndex += 2;

        foreach ($request->get('dataIvaDebitoCreditoCheque') as $income) {
            $sheet->setCellValue('D' . $rowIndex, $income['folio'])->setCellValue('E' . $rowIndex, $income['factura'])->setCellValue('F' . $rowIndex, $income['fecha'])->setCellValue('G' . $rowIndex, $income['importe'])->setCellValue('H' . $rowIndex, $income['iva'])->setCellValue('J' . $rowIndex, $income['total']);
            $rowIndex++;
        }

        $rowIndex += 2;
        $row1 = $rowIndex;
        $row2 = $rowIndex + 1;
        $sheet->getStyle("D$row1:J$row2")->getFont()->setBold(true);
        $sheet->getStyle("D$row1:J$row1")->getFill()->setFillType(Fill::FILL_PATTERN_GRAY0625);
        $sheet->getStyle("D$row1:J$row1")->getFont()->getColor()->setARGB(Color::COLOR_BLUE);

        $sheet->setCellValue("D$row1", 'Ingresos facturados A Crédito');
        $sheet->setCellValue("D$row2", 'RECIBO')->setCellValue("E$row2", 'FACTURA')->setCellValue("F$row2", 'FECHA PAGO')->setCellValue("G$row2", 'IMP. SIN IVA')->setCellValue("H$row2", 'IVA')->setCellValue("I$row2", 'PAGADO')->setCellValue("J$row2", 'TOTAL');
        $rowIndex += 2;

        foreach ($request->get('dataIvaCredito') as $income) {
            $sheet->setCellValue('D' . $rowIndex, $income['folio'])->setCellValue('E' . $rowIndex, $income['factura'])->setCellValue('F' . $rowIndex, $income['fecha'])->setCellValue('G' . $rowIndex, $income['importe'])->setCellValue('H' . $rowIndex, $income['iva'])->setCellValue('I' . $rowIndex, $income['pago'])->setCellValue('J' . $rowIndex, $income['total']);
            $rowIndex++;
        }

        $ingresosSinIva = doubleval($request->get('ingresosSinIva'));
        $ingresosConIva = doubleval($request->get('ingresosConIva'));
        $totalIvaIngresos = doubleval($request->get('totalIvaIngresos'));
        $totalCreIva = doubleval($request->get('totalCreIva'));

        $sheet->getStyle('A5:B8')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A5:B8')->getFill()->getStartColor()->setARGB('FFF5F7FA');
        $sheet->setCellValue('A5', 'Ingresos sin Iva')->setCellValue('B5', $ingresosSinIva);
        $sheet->setCellValue('A6', 'Ingresos con Iva')->setCellValue('B6', $ingresosConIva);
        $sheet->setCellValue('A7', 'Iva de Ingresos')->setCellValue('B7', $totalIvaIngresos);
        $sheet->setCellValue('A8', 'Créditos')->setCellValue('B8', $totalCreIva);

        $filename = $this->createFile($spreadsheet, 'INGRESOS');
        return response()->json(['file' => $filename,]);
    }

    private function createFile(Spreadsheet $spreadsheet, $name)
    {
        // create folder if not exists
        if (!file_exists('excel'))
            mkdir('excel', 0777, true);

        $filename = "excel/$name-" . date('Y-m-d') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        return $filename;
    }

    public function download(Request $request)
    {
        $filename = $request->get('file');
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
