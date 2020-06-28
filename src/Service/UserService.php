<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class UserService
{

    /** @var User */
    private $user;

    /** @var UserRepository */
    private $userRepository;

    /** @var YoubookPaginator */
    private $paginator;

    public function __construct(
        Security $security,
        UserRepository $userRepository,
        YoubookPaginator $paginator
    ) {
        $this->user = $security->getUser();
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
    }

    public function userIsTaken($username)
    {
        $user = $this->userRepository->loadUserByUsername($username);

        return [
            'isTaken' => !is_null($user)
        ];
    }

}