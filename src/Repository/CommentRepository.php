<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function persistComment(Comment $comment)
    {
        $em = $this->getEntityManager();
        $em->persist($comment);
        $em->flush();
    }

    public function findArticleCommentsToPagination($articleId)
    {
        $qb = $this->createQueryBuilder('findArticleCommentsToPagination');

        return $qb->select('c')
            ->from(Comment::class, 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->andWhere($qb->expr()->eq('c.article', ':articleId'))
            ->setParameter('articleId', $articleId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery();
    }

    public function deleteComment(Comment $comment)
    {
        $em = $this->getEntityManager();
        $comment->setUpdatedAt(new \DateTime());
        $comment->setDeleted(true);
        $em->persist($comment);
        $em->flush();
    }

    public function findArticleComment($articleId, $commentId)
    {
        $qb = $this->createQueryBuilder('findArticleComment');

        return $qb->select('c')
            ->from(Comment::class, 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->andWhere($qb->expr()->eq('c.commentId', ':commentId'))
            ->andWhere($qb->expr()->eq('c.article', ':articleId'))
            ->setParameter('commentId', $commentId)
            ->setParameter('articleId', $articleId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findPostComment($postId, $commentId)
    {
        $qb = $this->createQueryBuilder('findPostComment');

        return $qb->select('c')
            ->from(Comment::class, 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->andWhere($qb->expr()->eq('c.commentId', ':commentId'))
            ->andWhere($qb->expr()->eq('c.post', ':postId'))
            ->setParameter('commentId', $commentId)
            ->setParameter('postId', $postId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findActivityComment($activityId, $commentId)
    {
        $qb = $this->createQueryBuilder('findActivityComment');

        return $qb->select('c')
            ->from(Comment::class, 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->andWhere($qb->expr()->eq('c.commentId', ':commentId'))
            ->andWhere($qb->expr()->eq('c.activity', ':activityId'))
            ->setParameter('commentId', $commentId)
            ->setParameter('activityId', $activityId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findPostCommentsToPagination($postId)
    {
        $qb = $this->createQueryBuilder('findPostCommentsToPagination');

        return $qb->select('c')
            ->from(Comment::class, 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->andWhere($qb->expr()->eq('c.post', ':postId'))
            ->setParameter('postId', $postId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery();
    }

}
