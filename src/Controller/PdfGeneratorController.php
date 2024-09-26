<?php

namespace App\Controller;

use App\Entity\Story;
use App\Repository\StoryRepository;
use App\Service\PdfGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PdfGeneratorController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/pdf/{id}', name: 'app_pdf', methods: ['GET'])]
    public function generateStoryPdf(Story $story, PdfGeneratorService $pdfGeneratorService): Response
    {
        try {
            $htmlContent = $this->renderView('pdf_generator/index.html.twig', ['story' => $story]);
            $pdf = $pdfGeneratorService->generatePdf($htmlContent);

            $filename = sprintf('story-%d.pdf', $story->getId());

            return new Response(
                $pdf,
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]
            );
        } catch (\Exception $e) {
            // Log the exception
            $this->addFlash('error', 'An error occurred while generating the PDF.');
            return $this->redirectToRoute('story_show', ['id' => $story->getId()]);
        }
    }
}