<?php

namespace App\Repository;

use App\Entity\ArticleComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleComment[]    findAll()
 * @method ArticleComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleComment::class);
    }

    public function persistComment(ArticleComment $comment)
    {
        $em = $this->getEntityManager();
        $em->persist($comment);
        $em->flush();
    }

    public function findArticleCommentsToPagination($articleId)
    {
        $qb = $this->createQueryBuilder('findArticleCommentsToPagination');

        return $qb->select('ac')
            ->from(ArticleComment::class, 'ac')
            ->where($qb->expr()->eq('ac.deleted', 'false'))
            ->andWhere($qb->expr()->eq('ac.article', ':articleId'))
            ->setParameter('articleId', $articleId)
            ->orderBy('ac.createdAt', 'DESC')
            ->getQuery();
    }

    public function deleteComment(ArticleComment $comment)
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

        return $qb->select('ac')
            ->from(ArticleComment::class, 'ac')
            ->where($qb->expr()->eq('ac.deleted', 'false'))
            ->andWhere($qb->expr()->eq('ac.articleCommentId', ':commentId'))
            ->setParameter('commentId', $commentId)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
