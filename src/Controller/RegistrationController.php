<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use App\security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class RegistrationController extends AbstractController
{
        private $userRepository;
        private EmailVerifier $emailVerifier;

        public function __construct(UserRepository $userRepository,EmailVerifier $emailVerifier)
        {
            $this->userRepository = $userRepository;
            $this->emailVerifier = $emailVerifier;
        }

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

            return $this->redirectToRoute('app_send_verification_email', [
                'id' => $user->getId()
            ]);



        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);

    }


#[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
         $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Account Verified! You can now log in.');

        return $this->redirectToRoute('app_login');
    }


     #[Route("/resend-email-verification", name:"app_resend_email_verification")]

    public function resendEmailVerification(
    VerifyEmailHelperInterface $verifyEmailHelper,
    Request $request,AuthenticationUtils $authenticationUtils,
     MailerService $mailer):Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $user = $this->userRepository->findOneBy(['email' => $lastUsername]);
        if ($user->isIsVerified()) {
            $this->addFlash('success', 'Your email address is already verified.');
            return $this->redirectToRoute('app_login');
        }
        else{
           return $this->redirectToRoute('app_send_verification_email', [
                'id' => $user->getEmail()
            ]);


        }}

#[Route('/sendEmail/{id}',name:"app_send_verification_email")]
public function SendVerification($id,VerifyEmailHelperInterface $verifyEmailHelper,
MailerService $mailer){
    $user = $this->userRepository->findOneBy(['id' => $id]);

    $signatureComponents = $verifyEmailHelper->generateSignature('app_verify_email',
        $user->getId(),
        $user->getEmail(),
        ['id' => $user->getId()]);
        $link=$signatureComponents->getSignedUrl();
        $username=$user->getUsername();
        $body="<h2>Hello {$username},</h2>
        <h2>Thanks for your interest in creating an account.
        To create your account, please verify your email address by clicking below.</h2>
        <a href={$link}>
        <button style ='
            background-color: #008CBA ;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            display: block;
            margin: 0 auto;'>
      Click Here
    </button>
    </a>";
        $mailer->sendEmail($to=$user->getEmail(),$content=$body);
        $this->addFlash('success', 'A verification email has been sent to your email address.');

            return $this->render('registration/verification.html.twig');
}


}

