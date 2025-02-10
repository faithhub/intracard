<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    /**
     * Export data to Excel.
     *
     * @param  string  $filename
     * @param  array   $data
     * @param  string  $view
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportToExcel(string $filename, array $data, string $view)
    {
        $filename = $filename. '.xlsx';
        // dd($filename);
        return Excel::download(new \App\Exports\GenericExport($data, $view), $filename);
    }

    /**
     * Export data to PDF.
     *
     * @param  string  $filename
     * @param  array   $data
     * @param  string  $view
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportToPDF(string $filename, array $data, string $view)
    {
        $pdf = app('dompdf.wrapper'); // Create an instance of the PDF class
        $pdf->loadView($view, $data);
        // Configure PDF settings
        $pdf->setPaper('a4', 'landscape'); // Use landscape for wider tables
        $pdf->setOption([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'font_height' => '12',
            'enable_html5_parser' => true,
            'enable_remote' => true,
            'pdf_a' => false,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);
        return $pdf->download($filename . '.pdf');
    }
}
