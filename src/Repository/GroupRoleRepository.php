<?php

namespace App\Repository;

use App\Entity\GroupRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupRole[]    findAll()
 * @method GroupRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupRole::class);
    }

    // /**
    //  * @return GroupRole[] Returns an array of GroupRole objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupRole
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
