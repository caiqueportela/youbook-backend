<?php

namespace App\Repository;

use App\Entity\Chapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Chapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chapter[]    findAll()
 * @method Chapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chapter::class);
    }

    public function persistChapter(Chapter $chapter)
    {
        $em = $this->getEntityManager();
        $em->persist($chapter);
        $em->flush();
    }

    public function findChapters($courseId)
    {
        $qb = $this->createQueryBuilder('findChapters');

        $query = $qb->select('c')
            ->from(Chapter::class, 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->andWhere($qb->expr()->eq('c.course', ':courseId'))
            ->setParameter('courseId', $courseId)
            ->orderBy('c.createdAt', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function findChapter($courseId, $chapterId)
    {
        $qb = $this->createQueryBuilder('findChapter');

        $query = $qb->select('c')
            ->from(Chapter::class, 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->andWhere($qb->expr()->eq('c.course', ':courseId'))
            ->andWhere($qb->expr()->eq('c.chapterId', ':chapterId'))
            ->setParameter('courseId', $courseId)
            ->setParameter('chapterId', $chapterId)
            ->orderBy('c.createdAt', 'ASC');

        return $query->getQuery()->getOneOrNullResult();
    }

    public function deleteChapter(Chapter $chapter)
    {
        $em = $this->getEntityManager();
        $chapter->setUpdatedAt(new \DateTime());
        $chapter->setDeleted(true);
        $em->persist($chapter);
        $em->flush();
    }

}
