<?php

namespace App\Service;

use Exception;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotifyService
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function sendEmail(Email $email): void
    {
        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
