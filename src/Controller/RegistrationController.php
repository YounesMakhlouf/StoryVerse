<?php

namespace App\Controller;

use DateTime;
use DateTimeImmutable;
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
     UserPasswordHasherInterface $userPasswordHasher,
      EntityManagerInterface $entityManager,
      ): Response
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
            $currentDate = new DateTime('now');
            $user->setLastLoginDate($currentDate);
            $registrationDate = DateTimeImmutable::createFromMutable($currentDate); // convert DateTime to DateTimeImmutable
            $user->setRegistrationDate($registrationDate);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_send_verification_email', [
                'id' => $user->getId(),
                'resend'=>'0'
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);

    }


    #[Route('/verify/email/{id}', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator,$id): Response
    {

        try {
            $user = $this->userRepository->findOneBy(['id' => $id]);
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }


    #[Route('/sendEmail/{id}/{resend}', name: 'app_send_verification_email', requirements: [
        'id' => '^[1-9]\d*$',
        'resend' => '[01]'
    ])]
public function SendVerification($id,$resend='0',TemplatedEmail $email){

    $user = $this->userRepository->findOneBy(['id' => $id]);
    if(!$user){
        $this->addFlash('error', 'user not found.');

    }
    else{
        if($user->isIsVerified()){
            $this->addFlash('success', 'Your email is already verified ! ');
            return $this->redirectToRoute('app_login');
        }
        else{
    $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email'), ['id' => $id]
                    );
        if($resend=='1'){

            $this->addFlash('info', 'A new verification email has been sent to your email address.');
        }

}}
return $this->render('registration/verification.html.twig',['id'=>$id]);

}}

