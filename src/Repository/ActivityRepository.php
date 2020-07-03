<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function persistActivity(Activity $activity)
    {
        $em = $this->getEntityManager();
        $em->persist($activity);
        $em->flush();
    }

    public function findActivities($chapterId)
    {
        $qb = $this->createQueryBuilder('findActivities');

        $query = $qb->select('a')
            ->from(Activity::class, 'a')
            ->where($qb->expr()->eq('a.deleted', 'false'))
            ->andWhere($qb->expr()->eq('a.chapter', ':chapterId'))
            ->setParameter('chapter', $chapterId)
            ->orderBy('c.createdAt', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function findActivity($chapterId, $activityId)
    {
        $qb = $this->createQueryBuilder('findActivity');

        $query = $qb->select('a')
            ->from(Activity::class, 'a')
            ->where($qb->expr()->eq('a.deleted', 'false'))
            ->andWhere($qb->expr()->eq('a.chapter', ':chapterId'))
            ->andWhere($qb->expr()->eq('a.activityId', ':activityId'))
            ->setParameter('chapterId', $activityId)
            ->setParameter('chapterId', $chapterId)
            ->orderBy('c.createdAt', 'ASC');

        return $query->getQuery()->getOneOrNullResult();
    }

    public function deleteActivity(Activity $activity)
    {
        $em = $this->getEntityManager();
        $activity->setUpdatedAt(new \DateTime());
        $activity->setDeleted(true);
        $em->persist($activity);
        $em->flush();
    }

}
