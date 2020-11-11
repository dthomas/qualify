<?php

namespace App\Repository;

use App\Entity\Opportunity;
use App\Traits\HasPagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Opportunity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opportunity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opportunity[]    findAll()
 * @method Opportunity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpportunityRepository extends ServiceEntityRepository
{
    use HasPagination;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opportunity::class);
    }

    public function findAllOpportunities(int $page = 1, int $limit = 10, string $sort = 'createdAt' , bool $ascending = true)
    {
        $query = $this->createQueryBuilder('o')
            ->orderBy("o.{$sort}", $ascending ? 'ASC' : 'DESC')
            ->getQuery();
        
        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }
}

