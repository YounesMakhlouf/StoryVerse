<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\QuestRepository;
use App\Service\QuestCompletionService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request                     $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface      $entityManager,
                             QuestCompletionService      $questCompletionService,
                             QuestRepository             $questRepository): Response
    {
        // Create user and register form
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        // If form is submitted and valid, create user account
        if ($form->isSubmitted() && $form->isValid()) {
            // Set user avatar
            $user->setAvatar(($user->getGender() == 'female') ? 'female.png' : 'male.png');
            // Hash user's password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            // Set user's last login date
            $user->setLastLoginDate(new DateTime());
            $entityManager->persist($user);
            $entityManager->flush();
            // Mark login quest as completed
            $createAccountQuest = $questRepository->findOneBy(['requirement' => 'create_account']);
            if ($createAccountQuest) {
                $questCompletionService->markQuestAsCompleted($user, $createAccountQuest);
            }
            // Redirect to send verification email route
            return $this->redirectToRoute('app_send_verification_email', [
                'id' => $user->getId(),
                'resend' => '0'
            ]);
        }
        // Render registration form
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}