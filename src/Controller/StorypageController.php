<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Contribution;
use App\Form\CommentType;
use App\Form\ContributionType;
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
        $contribution=new Contribution();
        $ContributionForm = $this->createForm(ContributionType::class, $contribution);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setStory($story);
            $entityManager->persist($comment);
            $entityManager->flush();

//            // Return a JSON response with the new comment data
           return $this->json([
               'content' => $comment->getContent(),
               'author'=> $comment->getAuthor()->getUsername(),
               'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
               'count' =>$story->getComments()->count()

           ]);
        }
        $hasLiked=$story->getLikes()->contains($this->getUser());
        return $this->render('storypage/competed.html.twig', [

            'controller_name' => 'CompletedStoryController',
           'story'=>$story,
            'hasLiked'=>$hasLiked,
            'form' => $form->createView(),
            'contributionForm'=>$ContributionForm->createView()
        ]);
    }

    #[Route('/storypage/like/{id}', name: 'app_like')]

    public function addLike($id,Request $request, EntityManagerInterface $entityManager,StoryRepository $storyRepository)
    {
        $story=$storyRepository->find($id);
        $user=$this->getUser();
        if($story->getLikes()->contains($user)){
            $story->getLikes()->removeElement($user);

        }
        else{
            $story->addLike($user);
        }
        $entityManager->persist($story);
        $entityManager->flush();
        return $this->json([
            'count' => $story->getlikes()->count()
        ]);

    }

#[Route('/storypage/contribution/{id}', name: 'app_contribution')]
    public function addContribution($id,Request $request, EntityManagerInterface $entityManager,
                        StoryRepository $storyRepository)
{
    $story=$storyRepository->find($id);
    $user=$this->getUser();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $contribution->setAuthor($this->getUser());
        $contribution->setStory($story);
        $entityManager->persist($contribution);
        $entityManager->flush();
        return $this->json([
            'content' => $contribution->getContent(),
            'author'=> $contribution->getAuthor()->getUsername(),

        ]);
    }



    }




}
