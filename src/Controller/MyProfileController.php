<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// Include SluggerInterface for filename sanitization
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MyProfileController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private FileUploader $fileUploader;

    public function __construct(
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader
    ) {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/modify-profile', name: 'app_modify_profile')]
    public function modifyProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle avatar upload
            $file = $form->get('avatar')->getData();
            if ($file) {
                try {
                    $newFilename = $this->fileUploader->upload($file);
                    $user->setAvatar($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Failed to upload the avatar. Please try again.');
                    return $this->redirectToRoute('app_modify_profile');
                }
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Your profile has been updated.');

            return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
        }

        return $this->render('profile/modifyprofile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}