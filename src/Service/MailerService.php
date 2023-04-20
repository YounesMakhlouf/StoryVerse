<?php
namespace App\Service ;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\HttpFoundation\Response;
class MailerService{
    private  $transport ;
    private $mailer;
    public function __construct(){
        $this->transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $this->mailer=new Mailer($this->transport);
    }

    public function sendEmail($to='salmabouabidi019@gmail.com',
    $content='<p>See Twig integration for better HTML integration!</p>',
    $subject='Time for Symfony Mailer!'): void
   {
        $email = (new Email())

            ->from('storyverse19@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content);

            try {
                $this->mailer->send($email);
            
            } catch (TransportExceptionInterface $e) {            
            
            }

        // ...
    }
}