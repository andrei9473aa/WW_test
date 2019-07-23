<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findWithAuthorAndApplication($author, $application)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.author', 'a')
            ->andWhere('a = :author')
            ->setParameter('author', $author)
            ->innerJoin('c.application', 'app')
            ->andWhere('app = :app')
            ->setParameter('app', $application)
            ->orderBy('c.id', 'ASC')
            ->addSelect('a')
            ->getQuery()
            ->execute()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
