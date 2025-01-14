<?php

namespace App\Services\PdfToTextTransformer\Concrete;

use App\Services\PdfToTextTransformer\Interfaces\IPdfToTextConverter;
use Smalot\PdfParser\Parser as PdfParser;

class SmalotPdfParser implements IPdfToTextConverter
{
    protected PdfParser $pdfParser;

    public function __construct()
    {
        $this->pdfParser = new PdfParser();
    }

    public function convert(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $pdf = $this->pdfParser->parseFile($filePath);

        return $pdf->getText();
    }
}
