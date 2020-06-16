<?php

namespace App\Repository;

use App\Entity\Article;
use Cassandra\Date;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function persistArticle(Article $article)
    {
        $em = $this->getEntityManager();
        $em->persist($article);
        $em->flush();
    }

    public function findArticlesToPagination()
    {
        $qb = $this->createQueryBuilder('findArticlesToPagination');

        return $qb->select('a')
            ->from(Article::class, 'a')
            ->where($qb->expr()->eq('a.deleted', 'false'))
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery();
    }

    public function findArticle($articleId)
    {
        $qb = $this->createQueryBuilder('findArticle');

        return $qb->select('a')
            ->from(Article::class, 'a')
            ->where($qb->expr()->eq('a.deleted', 'false'))
            ->andWhere($qb->expr()->eq('a.articleId', ':articleId'))
            ->setParameter('articleId', $articleId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deleteArticle(Article $article)
    {
        $em = $this->getEntityManager();
        $article->setUpdatedAt(new \DateTime());
        $article->setDeleted(true);
        $em->persist($article);
        $em->flush();
    }

}
