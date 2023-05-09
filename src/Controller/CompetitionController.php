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
#[Route('/competition', name: 'competition')]
class CompetitionController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $competition1 = new Competition();
        $competition1->setName('Conan');
        $competition->setStartDate(new \DateTime());
        $competition->setEndDate(new \DateTime('tomorrow'));
        $cometition->setimageFilename('645a278a6bf66');
        $entityManager->persist($competition);
        $entityManager->flush();
        return $this->render('competition/index_initial.html.twig', [
            'controller_name' => 'CompetitionController',
        ]);
    }
    #[Route('/create', name: 'competition_create')]
    public function createCompetition(Request $request , EntityManagerInterface $entityManager): Response
    {
        $competition = new Competition();
        $form = $this->createForm(CompetitionType::class, $competition);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->get('title')->getData();
            $description = $form->get('description')->getData();
            $status = $form->get('status')->getData();
            $paid = $form->get('paid')->getData();
            $startsAt = $form->get('startsAt')->getData();
            $endsAt = $form->get('endsAt')->getData();
            $competition->setStartsAt($startsAt);
            $competition->setEndsAt($endsAt);
            $competition->setTitle($title);
            $competition->setDescription($description);
            $competition->setStatus($status);
            $competition->setPaid($paid);

            // handle file upload
            $imageFilename = $form->get('imageFilename')->getData();
            if ($imageFilename) {
                $newFilename = uniqid() . '.' . $imageFilename->guessExtension();

                try {
                    $imageFilename->move(
                        $this->getParameter('competition_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle the exception if something goes wrong
                }

                $competition->setImageFilename($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($competition);
            var_dump(); 
            $entityManager->flush();

            $this->addFlash('success', 'Competition created successfully!');
            return $this->redirectToRoute('competition');
        }
        $this->addFlash('success', 'Competition created successfully!');
        return $this->render('competition/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
