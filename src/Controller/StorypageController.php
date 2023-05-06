<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class StorypageController extends AbstractController
{   #[IsGranted('ROLE_USER')]
    #[Route('/storypage/{id}', name: 'app_storypage')]
    public function index($id,StoryRepository $storyRepository): Response
    {
        $story=$storyRepository->find($id);



        return $this->render('storypage/index.html.twig', [

            'controller_name' => 'CompletedStoryController',
           'story'=>$story,
        ]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/AddComment/{id}', name: 'app_add_comment')]
    public function addComment($id,StoryRepository $storyRepository,
                          Request $request,
                          EntityManagerInterface $entityManager): Response
    {
        $story=$storyRepository->find($id);
        $content = $request->query->get('content');
        $comment=new Comment();
            $comment->setContent($content)
                ->setStory($story)
                ->setAuthor($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_storypage', [
                'id'=>$id
            ]);
        }


}
