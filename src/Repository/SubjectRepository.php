<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Subject|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subject|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subject[]    findAll()
 * @method Subject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subject::class);
    }

    public function persistSubject(Subject $subject)
    {
        $em = $this->getEntityManager();
        $em->persist($subject);
        $em->flush();
    }

    public function findSubjects()
    {
        $qb = $this->createQueryBuilder('findSubjects');

        return $qb->select('s')
            ->from(Subject::class, 's')
            ->where($qb->expr()->eq('s.activated', 'true'))
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findSubject($subjectId)
    {
        $qb = $this->createQueryBuilder('findSubject');

        return $qb->select('s')
            ->from(Subject::class, 's')
            ->where($qb->expr()->eq('s.activated', 'true'))
            ->andWhere($qb->expr()->eq('s.subjectId', ':subjectId'))
            ->setParameter('subjectId', $subjectId)
            ->getQuery()
            ->getOneOrNullResult();
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
