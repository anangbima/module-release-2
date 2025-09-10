<?php

namespace Modules\DesaModuleTemplate\Services\Shared;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;
use Modules\DesaModuleTemplate\Exports\GenericExport;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\Response;

class ExportService
{
    /**
     * Export data to Excel.
     */
    public function exportToExcel(Collection $data, array $headings, string $fileName = 'export.xlsx', ?\Closure $mapper = null, string $format = ExcelFormat::XLSX): Response
    {
        $export = new GenericExport($data, $headings, $mapper);
        return Excel::download($export, $fileName, $format);
    }

    /**
     * Export data to PDF.
     */
    public function exportToPdf(Collection $data, string $view, string $fileName = 'export.pdf', array $viewData = [], array $paperSize = ['A4', 'portrait']): Response
    {
        // Load the view and pass the data to
        $pdf = Pdf::loadView($view, $viewData ?: ['data' => $data])->setPaper($paperSize[0], $paperSize[1]);
        return $pdf->download($fileName);
    }

    /**
     * Export to Docx.
     */
    public function exportToDocx(array $data, string $fileName = 'export.docx')
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        foreach ($data as $row) {
            $section->addText(implode(' | ', $row));
        }

        $tempFile = storage_path($fileName);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }

    /**
     * Export to Json
     */
    public function exportToJson(Collection $data, string $fileName = 'export.json'): Response
    {
        $tempFile = storage_path($fileName);

        file_put_contents($tempFile, $data->toJson(JSON_PRETTY_PRINT));

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}