<?php

namespace App\Service;

use Dompdf\Dompdf;

class PdfGeneratorService
{
    private Dompdf $domPdf;

    public function __construct()
    {
        $this->domPdf = new DomPdf();
    }

    public function generatePdf($html): ?string
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
//        $this->domPdf->stream("Story.pdf", [
//            'Attachment' => false
//        ]);
        return $this->domPdf->output();
    }
}