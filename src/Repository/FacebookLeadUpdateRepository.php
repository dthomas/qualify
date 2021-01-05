<?php

namespace App\Repository;

use App\Entity\FacebookLeadUpdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FacebookLeadUpdate|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacebookLeadUpdate|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacebookLeadUpdate[]    findAll()
 * @method FacebookLeadUpdate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacebookLeadUpdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacebookLeadUpdate::class);
    }

    // /**
    //  * @return FacebookLeadUpdate[] Returns an array of FacebookLeadUpdate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FacebookLeadUpdate
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
