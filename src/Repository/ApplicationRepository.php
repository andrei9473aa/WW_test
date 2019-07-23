<?php

namespace App\Repository;

use App\Entity\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Application|null find($id, $lockMode = null, $lockVersion = null)
 * @method Application|null findOneBy(array $criteria, array $orderBy = null)
 * @method Application[]    findAll()
 * @method Application[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Application::class);
    }

    public function findManaged()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.manager IS NOT NULL')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->execute()
        ;
    }

    public function findWithNoManager()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.manager IS NULL')
            ->getQuery()
            ->execute()
        ;
    }
}
