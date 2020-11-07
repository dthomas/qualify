<?php

namespace App\Repository;

use App\Entity\LeadInteraction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeadInteraction|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeadInteraction|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeadInteraction[]    findAll()
 * @method LeadInteraction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeadInteractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeadInteraction::class);
    }

    // /**
    //  * @return LeadInteraction[] Returns an array of LeadInteraction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LeadInteraction
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
