<?php

namespace App\Repository;

use App\Entity\PostComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method PostComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostComment[]    findAll()
 * @method PostComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCommentRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostComment::class);
    }

    public function persistComment(PostComment $comment)
    {
        $em = $this->getEntityManager();
        $em->persist($comment);
        $em->flush();
    }

    public function findPostCommentsToPagination($postId)
    {
        $qb = $this->createQueryBuilder('findPostCommentsToPagination');

        return $qb->select('pc')
            ->from(PostComment::class, 'pc')
            ->where($qb->expr()->eq('pc.deleted', 'false'))
            ->andWhere($qb->expr()->eq('pc.post', ':postId'))
            ->setParameter('postId', $postId)
            ->orderBy('pc.createdAt', 'DESC')
            ->getQuery();
    }

    public function deleteComment(PostComment $comment)
    {
        $em = $this->getEntityManager();
        $comment->setUpdatedAt(new \DateTime());
        $comment->setDeleted(true);
        $em->persist($comment);
        $em->flush();
    }

    public function findComment($commentId)
    {
        $qb = $this->createQueryBuilder('findComment');

        return $qb->select('pc')
            ->from(PostComment::class, 'pc')
            ->where($qb->expr()->eq('pc.deleted', 'false'))
            ->andWhere($qb->expr()->eq('pc.postCommentId', ':commentId'))
            ->setParameter('commentId', $commentId)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
