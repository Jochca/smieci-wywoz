<?php

namespace App\Services\PDF;

use Smalot\PdfParser\Parser as PdfParser;

class PdfToTxtService
{
    protected PdfParser $pdfParser;

    public function __construct()
    {
        $this->pdfParser = new PdfParser();
    }

    /**
     * Convert PDF file to plain text.
     *
     * @param string $filePath
     * @return string
     * @throws \Exception
     */
    public function convertToText(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $pdf = $this->pdfParser->parseFile($filePath);

        return $pdf->getText();
    }
}
