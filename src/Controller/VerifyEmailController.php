<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class VerifyEmailController extends AbstractController
{
    private UserRepository $userRepository;
    private EmailVerifier $emailVerifier;

    public function __construct(UserRepository $userRepository, EmailVerifier $emailVerifier)
    {
        $this->userRepository = $userRepository;
        $this->emailVerifier = $emailVerifier;
    }


    #[Route('/verify/email/{id}', name: 'app_verify_email')]
public function verifyUserEmail(Request $request, TranslatorInterface $translator, $id): Response
{
    try {
        $user = $this->userRepository->findUserById($id);
        $this->emailVerifier->handleEmailConfirmation($request, $user);
    } catch (VerifyEmailExceptionInterface $exception) {
        $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

        return $this->redirectToRoute('app_register');
    }

    $this->addFlash('success', 'Your email address has been verified ! Please sign in to continue');

    return $this->redirectToRoute('app_login');
}









    #[Route('/sendEmail/{id}/{resend}', name: 'app_send_verification_email', requirements: [
        'id' => '^[1-9]\d*$',
        'resend' => '[01]'
    ])]

    public function SendVerification( $id, $resend = '0'):Response
    {$user = $this->userRepository->findUserById($id);
        if (!$user) {
            $this->addFlash('error', 'user not found.');

        } else {
            if ($user->isIsVerified()) {
                $this->addFlash('success', 'Your email is already verified ! ');
                return $this->redirectToRoute('app_login');
            } else {
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email'), ['id' => $id]
                );
                if ($resend == '1') {

                    $this->addFlash('info', 'A new verification email has been sent to your email address.');
                }

            }
        }
        return $this->render('registration/verification.html.twig', ['id' => $id]);
    }
}
