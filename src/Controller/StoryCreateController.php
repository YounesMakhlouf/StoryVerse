<?php
namespace App\Controller;
use App\Entity\Story;
use App\Entity\Contribution;
use App\Form\StoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;

class StoryCreateController extends AbstractController
{
    #[Route('/story/new', name: 'Story_new')]
 
    public function new(Request $request, EntityManagerInterface $entityManager)
    {  
        $story = new Story();
        $contribution = new Contribution();
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // handle image upload
            $imageFile = $form->get('storyImage')->getData();
            $contribution->setContent($form->get('firstContribution')->getData());
            $contribution->setAuthor($this->getUser());
            $story->addContribution($contribution);
           
            $story->setCreatedAt(new \DateTime());

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('story_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                    $this->addFlash('danger', 'An error occurred while uploading the image.');
                }

                // updates the 'imageFilename' property to store the image file name
                $story->setStoryImage($newFilename);
               
            }

            // save the story to the database
            $entityManager->persist($contribution);
            $entityManager->persist($story);
            $entityManager->flush();

            return $this->redirectToRoute('app_storypage', ['id' => $story->getId()]);
        }

        return $this->render('story_create\index.html.twig', [
            'Story' => $story,
            'form' => $form->createView(),
        ]);
    }
}
