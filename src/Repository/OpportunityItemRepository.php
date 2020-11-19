<?php

namespace App\Repository;

use App\Entity\OpportunityItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OpportunityItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpportunityItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpportunityItem[]    findAll()
 * @method OpportunityItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpportunityItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpportunityItem::class);
    }

    // /**
    //  * @return OpportunityItem[] Returns an array of OpportunityItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OpportunityItem
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
