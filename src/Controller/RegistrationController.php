<?php

namespace App\Controller;

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
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

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

            // do anything else you need here, like send an email
            // return $this->redirectToRoute('app_verify_email',
            //     [$link]
            return $this->render('registration/verification.html.twig');

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    
    }

//      #[Route("/verify{link}",name:'app_verify_email')]
//      public function verifyUserEmail(Request $request,
//       VerifyEmailHelperInterface $verifyEmailHelper,
//       UserRepository $userRepository,
//       EntityManagerInterface $entityManager,
//       $link=null
//       )
//       : Response
//       {
//          //$request->query->get('id') retrieves the value of the 'id' query parameter from the current HTTP request
//          if(is_null($request->query->get('id')))
//          {return $this->render('registration/verification.html.twig',[
//              'link'=>$link,
//          ]);}
//          $user = $userRepository->find($request->query->get('id'));
//                   if (!$user) {
//              throw $this->createNotFoundException();
//          }

//          try {
//              $verifyEmailHelper->validateEmailConfirmation(
//                  $request->getUri(),
//                  $user->getId(),
//                  $user->getEmail(),
//              );
//          }
//          catch (VerifyEmailExceptionInterface $e) {
//              $this->addFlash('error', $e->getReason());
//              return $this->redirectToRoute('app_register');
//          }
//          $user->setIsVerified(true);
//          $entityManager->flush();
//          $this->addFlash('success', 'Account Verified! You can now log in.');
//          return $this->redirectToRoute('app_home');
//  }}

#[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_home');
    }
}

