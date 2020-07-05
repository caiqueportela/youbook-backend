<?php

namespace App\Service;

use App\Entity\Group;
use App\Entity\GroupUser;
use App\Entity\User;
use App\Repository\GroupRoleRepository;
use App\Repository\UserRepository;
use App\Repository\UserRoleRepository;
use App\Security\ApiVoter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserService
{

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var User */
    private $user;

    /** @var UserRepository */
    private $userRepository;

    /** @var YoubookPaginator */
    private $paginator;

    /** @var GroupRoleRepository */
    private $groupRoleRepository;

    /** @var UserRoleRepository */
    private $userRoleRepository;

    public function __construct(
        Security $security,
        UserRepository $userRepository,
        YoubookPaginator $paginator,
        GroupRoleRepository $groupRoleRepository,
        UserRoleRepository $userRoleRepository,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->user = $security->getUser();
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
        $this->groupRoleRepository = $groupRoleRepository;
        $this->userRoleRepository = $userRoleRepository;
        $this->encoder = $encoder;
    }

    public function userIsTaken($username)
    {
        $user = $this->userRepository->loadUserByUsername($username);

        return [
            'isTaken' => !is_null($user)
        ];
    }

    public function listUsers(int $page)
    {
        $users = $this->userRepository->findUsersToPagination();

        $this->paginator->setCurrentPage($page);
        $this->paginator->setQuery($users);

        return $this->paginator->paginate();
    }

    public function createUser($username, $password, $email, $role): User
    {
        $user = new User();
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setUsername($username);

        $roleUser = $this->userRoleRepository->findOneByName(ApiVoter::USER_ROLE);
        $user->addRole($roleUser);

        if ($this->currentUserIsAdmin()) {
            switch ($role) {
                case 'admin':
                    $this->addAdminToUser($user);
                    break;
                case 'author':
                    $this->addAuthorToUser($user);
                    break;
            }
        }

        $this->userRepository->persistUser($user);

        return $user;
    }

    private function currentUserIsAdmin()
    {
        return (in_array(ApiVoter::ADMIN_ROLE, $this->user->getRoles()));
    }

    private function addAuthorToUser(User $user)
    {
        $roleAuthor = $this->userRoleRepository->findOneByName(ApiVoter::AUTHOR_ROLE);
        $user->addRole($roleAuthor);

        $group = new Group();
        $group->setName($user->getUsername());

        $groupUser = new GroupUser();
        $groupUser->setGroup($group);
        $groupUser->setUser($user);

        $roleGroupAdmin = $this->groupRoleRepository->findOneByName(ApiVoter::GROUP_ADMIN_ROLE);
        $groupUser->addRole($roleGroupAdmin);

        $user->addGroupUser($groupUser);
    }

    private function addAdminToUser(User $user)
    {
        $roleAdmin = $this->userRoleRepository->findOneByName(ApiVoter::ADMIN_ROLE);
        $user->addRole($roleAdmin);
    }

}