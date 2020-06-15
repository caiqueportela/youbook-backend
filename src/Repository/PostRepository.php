<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findPostsToPagination()
    {
        $qb = $this->createQueryBuilder('findPostsToPagination');

        return $qb->select('p')
            ->from(Post::class, 'p')
            ->where($qb->expr()->eq('p.deleted', 'false'))
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();
    }

    public function persistPost(Post $post)
    {
        $em = $this->getEntityManager();
        $em->persist($post);
        $em->flush();
    }

    public function deletePost(Post $post)
    {
        $em = $this->getEntityManager();
        $post->setUpdatedAt(new \DateTime());
        $post->setDeleted(true);
        $em->persist($post);
        $em->flush();
    }

    public function findPost($postId)
    {
        $qb = $this->createQueryBuilder('findPost');

        return $qb->select('p')
            ->from(Post::class, 'p')
            ->where($qb->expr()->eq('p.deleted', 'false'))
            ->andWhere($qb->expr()->eq('p.postId', ':postId'))
            ->setParameter('postId', $postId)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
