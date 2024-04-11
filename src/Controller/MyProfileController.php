<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MyProfileController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/modify-profile', name: 'app_modify_profile')]
    public function modifyProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->handleAvatarUpload($form, $user);
            } catch (FileException $e) {
                throw new FileException('Failed to upload file: ' . $e->getMessage());
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
        }

        return $this->render('profile/modifyprofile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    private function handleAvatarUpload($form, $user): void
    {
        $file = $form->get('avatar')->getData();
        if ($file instanceof UploadedFile) {
            $newFilename = $this->generateFilename($file);
            $file->move($this->getParameter('avatar_directory'), $newFilename);
            $user->setAvatar($newFilename);
        }
    }

    private function generateFilename(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        return $safeFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();
    }

}