<?php

namespace App\Repository;

use App\Entity\FacebookPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FacebookPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacebookPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacebookPage[]    findAll()
 * @method FacebookPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacebookPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacebookPage::class);
    }

    // /**
    //  * @return FacebookPage[] Returns an array of FacebookPage objects
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
    public function findOneBySomeField($value): ?FacebookPage
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
