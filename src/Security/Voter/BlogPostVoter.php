<?php

namespace App\Security\Voter;

use App\Entity\BlogPost;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BlogPostVoter extends Voter
{
    const VIEW = 'view';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW])
            && $subject instanceof BlogPost;
    }

    protected function voteOnAttribute(string $attribute, $post, TokenInterface $token): bool
    {
        // $user = $token->getUser();
        // // if the user is anonymous, do not grant access
        // if (!$user instanceof UserInterface) {
        //     return false;
        // }

        return match($attribute) {
            self::VIEW => $this->canView($post),
            default => false,
        };
    }

    private function canView(BlogPost $post): bool
    {
        return $post->getStatus() === BlogPost::STATUS_ACTIVE;
    }
}






