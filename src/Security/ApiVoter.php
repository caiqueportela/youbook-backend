<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ApiVoter extends Voter
{

    const USER_ROLE = 'user';
    const ADMIN_ROLE = 'admin';

    protected function supports(string $attribute, $subject)
    {
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (in_array(self::ADMIN_ROLE, $user->getRolesName())) {
            return true;
        }

        return in_array($attribute, $user->getRolesName());
    }

}