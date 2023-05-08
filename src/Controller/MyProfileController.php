<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
            'stories'=> $contributedStories,
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
            $avatar = $form->get('avatar')->getData();
            if ($avatar) {
                $user->setAvatar($avatar);
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
