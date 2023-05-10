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
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getGender() == 'female') {
                $user->setAvatar('female.png');
            } else {
                $user->setAvatar('male.png');

            }
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setLastLoginDate(new DateTime());
            $entityManager->persist($user);
            $entityManager->flush();
            $createAccountQuest = $questRepository->findOneBy(['requirement' => 'create_account']);

            $questCompletionService->markQuestAsCompleted($user, $createAccountQuest);
            return $this->redirectToRoute('app_send_verification_email', [
                'id' => $user->getId(),
                'resend' => '0'
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

