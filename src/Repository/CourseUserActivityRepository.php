<?php

namespace App\Repository;

use App\Entity\CourseUserActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CourseUserActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseUserActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseUserActivity[]    findAll()
 * @method CourseUserActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseUserActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseUserActivity::class);
    }

    // /**
    //  * @return CourseUserActivity[] Returns an array of CourseUserActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CourseUserActivity
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
