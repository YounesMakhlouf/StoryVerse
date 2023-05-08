<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use App\Service\PdfGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfGeneratorController extends AbstractController
{
    #[Route('/pdf/{id}', name: 'app_pdf')]
    public function storyPdf($id,StoryRepository $storyRepository, PdfGeneratorService $pdfGeneratorService) {
        $story=$storyRepository->find($id);
        $html = $this->render('pdf_generator/index.html.twig', ['story' => $story]);
        $pdfGeneratorService->GeneratePdf($html);
        return $this->render('pdf_generator/index.html.twig', ['story' => $story]);
    }
}
