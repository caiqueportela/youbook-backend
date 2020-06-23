<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ApiVoter extends Voter
{

    const USER_ROLE = 'user';
    const AUTHOR_ROLE = 'author';
    const ADMIN_ROLE = 'admin';

    const GROUP_ADMIN_ROLE = 'admin';
    const GROUP_ARTICLE_AUTHOR_ROLE = 'article_author';
    const GROUP_COURSE_AUTHOR_ROLE = 'course_author';
    const GROUP_ARTICLE_EDITOR_ROLE = 'article_editor';
    const GROUP_COURSE_EDITOR_ROLE = 'course_editor';

    protected function supports(string $attribute, $subject)
    {
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (in_array(self::ADMIN_ROLE, $user->getRoles())) {
            return true;
        }

        return in_array($attribute, $user->getRoles());
    }

}