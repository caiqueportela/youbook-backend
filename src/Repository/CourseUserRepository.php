<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\CourseUser;
use App\Entity\CourseUserActivity;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

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
            ->orderBy('cu.startedAt', 'DESC');

        return $query->getQuery()->getOneOrNullResult();
    }

    public function persistCourseUser(CourseUser $courseUser)
    {
        $em = $this->getEntityManager();
        $em->persist($courseUser);
        $em->flush();
    }

    public function findCoursesPurchasedToPagination(string $search, User $user)
    {
        $qb = $this->createQueryBuilder('findCoursesPurchasedToPagination');

        $search = strtolower($search);

        $query = $qb->select('cu')
            ->from(CourseUser::class, 'cu')
            ->innerJoin('cu.course', 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->andWhere($qb->expr()->eq('cu.owner', ':userId'))
            ->setParameter('userId', $user->getUserId())
            ->orderBy('cu.startedAt', 'DESC');

        if ($search) {
            $query->andWhere($query->expr()->orX([
                $qb->expr()->like('LOWER(c.title)', $search),
                $qb->expr()->like('LOWER(c.subtitle)', $search),
                $qb->expr()->like('LOWER(c.description)', $search),
            ]));
        }

        return $query->getQuery();
    }

}
