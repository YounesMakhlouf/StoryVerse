<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;


class MyProfileController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/myprofile', name: 'app_myprofile')]
    public function showMyProfile(): Response
    {
        $user = $this->getUser();
        $contributedStories = $user->getContributedStories();

        return $this->render('profile/myprofile.html.twig', [
            'user' => $user,
            'stories' => $contributedStories

        ]);
    }

    #[Route('/modify-profile', name: 'app_modify_profile')]
    public function modifyProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Check if user is authenticated
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $File = $form->get('avatar')->getData();
            
            // this condition is needed because the 'avatar' field is not required
            // so the image  must be processed only when a file is uploaded
           
          if ($File) {
                $originalFilename = pathinfo($File->getClientOriginalName(), PATHINFO_FILENAME);
            
                $newFilename = $originalFilename.'-'.uniqid().'.'.$File->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $File->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setAvatar($newFilename);
            }
            

            // Persist changes to database
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Your profile has been updated successfully!');

            return $this->redirectToRoute('app_myprofile');
        }

        return $this->render('profile/modifyprofile.html.twig', [
            'form' => $form->createView(),
            'errors' => $form->getErrors(true),
            'user' => $user
        ]);
    }
}
