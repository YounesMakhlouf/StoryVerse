<?php

namespace App\Controller;


namespace App\Controller;

use App\Entity\Competition;
use App\Form\CompetitionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompetitionController extends AbstractController
{
    #[Route('/competition/create', name: 'competition_create')]
    public function createCompetition(Request $request, EntityManagerInterface $entityManager): Response
    {
        $competition = new Competition();
        $form = $this->createForm(CompetitionType::class, $competition);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $competition = $form->getData();

            // handle file upload
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('competition_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle the exception if something goes wrong
                }

                $competition->setImageFilename($newFilename);
            }

            $entityManager->persist($competition);
            $entityManager->flush();

            $this->addFlash('success', 'Competition created successfully!');
            return $this->redirectToRoute('competition');
        }

        return $this->render('competition/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
