<?php

namespace App\Repository;

use App\Entity\ApplicationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ApplicationStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationStatus[]    findAll()
 * @method ApplicationStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ApplicationStatus::class);
    }

    // /**
    //  * @return ApplicationStatus[] Returns an array of ApplicationStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApplicationStatus
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
