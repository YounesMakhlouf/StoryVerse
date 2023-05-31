<?php

namespace App\Controller;

use App\Entity\Contribution;
use App\Entity\Story;
use App\Form\StoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoryCreateController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/story/new', name: 'Story_new')]
    public function new(Request $request): RedirectResponse|Response
    {
        $story = new Story();
        $contribution = new Contribution();
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->handleImageUpload($story, $form);
            } catch (FileException $e) {
                $this->addFlash('danger', 'An error occurred while uploading the image: ' . $e->getMessage());
                return $this->redirectToRoute('Story_new');
            }

            $contribution->setContent($form->get('firstContribution')->getData());
            $contribution->setAuthor($this->getUser());
            $story->addContribution($contribution);

            // save the story to the database
            $this->entityManager->persist($contribution);
            $this->entityManager->persist($story);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_storypage', ['id' => $story->getId()]);
        }
        return $this->render('story_create\index.html.twig', [
            'Story' => $story,
            'form' => $form->createView(),
        ]);
    }

    private function handleImageUpload(Story $story, $form): void
    {
        $imageFile = $form->get('storyImage')->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
            $imageFile->move(
                $this->getParameter('story_directory'),
                $newFilename
            );
            $story->setStoryImage($newFilename);
        } else {
            $story->setStoryImage($story->getGenre() . '.jpg');
        }
    }
}
