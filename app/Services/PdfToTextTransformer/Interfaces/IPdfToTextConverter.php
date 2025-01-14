<?php

namespace App\Services\PdfToTextTransformer\Interfaces;

interface IPdfToTextConverter
{
    public function convert(string $filePath): string;
}
