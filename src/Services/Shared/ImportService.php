<?php

namespace Modules\DesaModuleTemplate\Services\Shared;

use Imagick;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Smalot\PdfParser\Parser;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ImportService
{
    /**
     * Parse CSV to collection.
     */
    public function parseCsv(UploadedFile $file) : Collection
    {
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_map('strtolower', array_shift($rows));

        return collect($rows)->map(function ($row) use ($header) {
            return collect($header)->combine($row);
        });
    }
    
    /**
     * Parse XLSX to collection.
     */
    public function parseXlsx(UploadedFile $file): Collection
    {
        $rows = Excel::toCollection(null, $file)->first();

        if ($rows->isEmpty()) {
            return collect();
        }

        $header = array_map('strtolower', $rows->shift()->toArray());

        return $rows->map(function ($row) use ($header) {
            return collect($header)->combine($row->toArray());
        });
    }

    /**
     * Parse JSON to collection.
     */
    public function parseJson(UploadedFile $file): Collection
    {
        $content = file_get_contents($file->getRealPath());
        $data = json_decode($content, true);

        return collect($data)->map(function ($item) {
            return collect($item)->keyBy(fn($_, $k) => strtolower($k));
        });
    }

    /**
     * Parse Pdf to collection using smalot
     */
    public function parsePdf(UploadedFile $file): Collection
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($file->getRealPath());

        $text = $pdf->getText();
        
        $rows = explode("\n", $text);
        // dd($rows);
        
        dd(strlen($text), $text);
        
        $parsedRows = collect($rows)
                        ->map(fn($row) => trim($row))
                        ->filter()
                        ->map(fn($row) => preg_split('/\s+/', $row));

        $header = array_map('strtolower', $parsedRows->shift());

        return $parsedRows->map(function ($row) use ($header) {
            if (count($row) !== count($header)) {
                return collect(); 
            }

            return collect($header)->combine($row);
        });
    }

    /**
     * Parse Pdf to collection using ocr
     */
    public function parsePdfOcr(UploadedFile $file): Collection
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($file->getRealPath());

        $text = $pdf->getText();

        // dd(strlen($text), $text);

        if (strlen(trim($text)) > 0) {
            $rows = explode("\n", $text);

            $parsedRows = collect($rows)
                ->map(fn($row) => trim($row))
                ->filter()
                ->map(fn($row) => preg_split('/\s+/', $row));

            $header = array_map('strtolower', $parsedRows->shift() ?? []);

            return $parsedRows->map(function ($row) use ($header) {
                if (count($row) !== count($header)) {
                    return collect();
                }

                return collect($header)->combine($row);
            });
        }

        if (stripos(PHP_OS, 'WIN') === 0) {
            putenv('PATH=' . getenv('PATH') . ';C:/Program Files/gs/gs10.05.1/bin');
        }

        // === OCR fallback ===
        try {
            $imagick = new Imagick();
            $imagick->setResolution(300, 300);
            $imagick->readImage($file->getRealPath());
            $imagick->setImageFormat('jpeg');
        } catch (\Exception $e) {
            throw new \RuntimeException("Gagal memproses PDF untuk OCR: " . $e->getMessage());
        }

        $allText = [];

        foreach ($imagick as $index => $page) {
            $imagePath = storage_path("app/temp_ocr_page_{$index}.jpg");
            $page->writeImage($imagePath);

            $ocrText = (new TesseractOCR($imagePath))
                ->lang('ind')
                ->run();

            $allText[] = $ocrText;

            @unlink($imagePath);
        }

        $imagick->clear();
        $imagick->destroy();

        $rows = explode("\n", implode("\n", $allText));

        $parsedRows = collect($rows)
            ->map(fn($row) => trim($row))
            ->filter()
            ->map(fn($row) => preg_split('/\s+/', $row));

        $header = array_map('strtolower', $parsedRows->shift() ?? []);

        // dd($header, $parsedRows);

        return $parsedRows->map(function ($row) use ($header) {
            if (count($row) !== count($header)) {
                return collect();
            }
            return collect($header)->combine($row);
        });
    }
}