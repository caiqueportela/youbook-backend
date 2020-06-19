<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function findComment($commentId)
    {
        $qb = $this->createQueryBuilder('findComment');

        return $qb->select('c')
            ->from(Comment::class, 'c')
            ->where($qb->expr()->eq('c.deleted', 'false'))
            ->andWhere($qb->expr()->eq('c.commentId', ':commentId'))
            ->setParameter('commentId', $commentId)
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
