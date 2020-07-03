<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function persistCourse(Course $course)
    {
        $em = $this->getEntityManager();
        $em->persist($course);
        $em->flush();
    }

    public function findCourse($courseId)
    {
        $qb = $this->createQueryBuilder('findCourse');

        return $qb->select('c')
            ->from(Course::class, 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->andWhere($qb->expr()->eq('c.courseId', ':courseId'))
            ->setParameter('courseId', $courseId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deleteCourse(Course $course)
    {
        $em = $this->getEntityManager();
        $course->setUpdatedAt(new \DateTime());
        $course->setDeleted(true);
        $em->persist($course);
        $em->flush();
    }


    public function findCoursesToPagination(string $search = null)
    {
        $qb = $this->createQueryBuilder('findCoursesToPagination');

        $search = strtolower($search);

        $query = $qb->select('c')
            ->from(Course::class, 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->orderBy('c.createdAt', 'DESC');

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
