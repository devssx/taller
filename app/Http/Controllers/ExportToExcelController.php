<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//use PhpOffice\PhpSpreadsheet\IOFactory;


class ExportToExcelController extends Controller
{
    public function index()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()->setCreator('Taller Gallegos')->setTitle('Gastos');

        $sheet->setCellValue('A1', 'Hello World !');

        if (!file_exists('files'))
            mkdir('files', 0777, true);
        
        $filename = 'files/GASTOS' . date('Y-m-d') . '.xlsx';
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
