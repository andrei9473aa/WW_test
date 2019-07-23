<?php

namespace App\Security\Voter;

use App\Entity\Application;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ApplicationVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['WATCH', 'EDIT', 'DELETE', 'MANAGE'])
            && $subject instanceof Application;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Application $subject */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'WATCH':
                if($this->security->isGranted(['ROLE_MODERATOR', 'ROLE_MANAGER'])){
                    return true;
                }
                if($subject->getAuthor() == $user) {
                    return true;
                }
                break;
            case 'EDIT':
                if($subject->getAuthor() == $user) {
                    return true;
                }
                break;
            case 'DELETE':
                if($subject->getAuthor() == $user) {
                    return true;
                }
                break;
            case 'MANAGE':
                if($subject->getManager() == $user) {
                    return true;
                }
                break;
        }

        return false;
    }
}
