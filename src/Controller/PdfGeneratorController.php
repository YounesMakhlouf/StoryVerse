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
    public function generateStoryPdf(int $id, StoryRepository $storyRepository, PdfGeneratorService $pdfGeneratorService): Response
    {
        $story = $storyRepository->find($id);
        if (!$story) {
            throw $this->createNotFoundException('Story not found');
        }
        $pdf = $pdfGeneratorService->generatePdf($this->renderView('pdf_generator/index.html.twig', ['story' => $story]));
        // return pdf with appropriate headers
        return new Response(
            $pdf,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="story.pdf"',
            ]
        );
    }
}