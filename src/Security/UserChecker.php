<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        // if (!$user instanceof User) {
        //     return;
        // }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->isBlocked()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException("Votre compte est bloqué !");
        }

        if ($user->getStatus() === User::STATUS_PENDING_EMAIL_VERIFICATION) {
            throw new CustomUserMessageAccountStatusException("Veuillez valider votre email.");
        }
    }
}
