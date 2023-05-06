<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class StorypageController extends AbstractController
{   #[IsGranted('ROLE_USER')]
    #[Route('/storypage/{id}', name: 'app_storypage')]
    public function index( Request $request,$id,StoryRepository $storyRepository,EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $story=$storyRepository->find($id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setStory($story);
            $entityManager->persist($comment);
            $entityManager->flush();

            // Return a JSON response with the new comment data
            return $this->json([
                'content' => $comment->getContent(),
            ]);
        }

        return $this->render('storypage/competed.html.twig', [
            'controller_name' => 'CompletedStoryController',
           'story'=>$story,
            'form' => $form->createView(),
        ]);
    }





}
