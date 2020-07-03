<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\CourseUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CourseUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseUser[]    findAll()
 * @method CourseUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseUser::class);
    }

    public function findUserInCourse(User $user, Course $course)
    {
        $qb = $this->createQueryBuilder('findUserInCourse');

        $query = $qb->select('cu')
            ->from(CourseUser::class, 'cu')
            ->andWhere($qb->expr()->eq('cu.course', ':courseId'))
            ->andWhere($qb->expr()->eq('cu.owner', ':userId'))
            ->setParameter('courseId', $course->getCourseId())
            ->setParameter('userId', $user->getUserId())
            ->orderBy('c.createdAt', 'ASC');

        return $query->getQuery()->getOneOrNullResult();
    }

}
