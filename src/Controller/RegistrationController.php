<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MailerService;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use App\Repository\UserRepository;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request,
    MailerService $mailer,
     UserPasswordHasherInterface $userPasswordHasher,
      EntityManagerInterface $entityManager,
      VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $signatureComponents = $verifyEmailHelper->generateSignature('app_verify_email',
        $user->getId(),
        $user->getEmail(),
        ['id' => $user->getId()]);
        $link=$signatureComponents->getSignedUrl();
        $username=$user->getUsername();
        $body="<div>Hello {$username},</div>
        <div>Thanks for your interest in creating an account.
        To create your account, please verify your email address by clicking below.</div>
        <button style ='{
            background-color: #008CBA ;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }'
          onclick='window.location.href={$link}';>
      Click Here
    </button>";
        $mailer->sendEmail($to=$user->getEmail(),$content=$body);

            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_verify_email',array('user' => $user));
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route("/verify",name:'app_verify_email')]
    public function verifyUserEmail(Request $request,
     VerifyEmailHelperInterface $verifyEmailHelper,
     UserRepository $userRepository,
     $user)
     : Response
     {
        try {
            $verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $user->getId(),
                $user->getEmail(),
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', $e->getReason());
            return $this->redirectToRoute('app_register');
        }

    }
}
