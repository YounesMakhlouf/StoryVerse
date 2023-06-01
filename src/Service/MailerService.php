<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

class MailerService
{
    private Transport\TransportInterface $transport;
    private Mailer $mailer;

    public function __construct()
    {
        $this->transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $this->mailer = new Mailer($this->transport);
    }

    public function sendEmail(TemplatedEmail $email): void
    {
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
    }
}