<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function loadUserByUsername(string $usernameOrEmail)
    {
        $qb = $this->createQueryBuilder('loadUserByUsername');

        return $qb->select('u')
                ->from(User::class, 'u')
                ->where($qb->expr()->orX(
                    $qb->expr()->eq('u.username', ':usernameOrEmail'),
                    $qb->expr()->eq('u.email', ':usernameOrEmail')
                ))
            ->setParameter('usernameOrEmail', $usernameOrEmail)
            ->getQuery()
            ->getOneOrNullResult();
    }

}