<?php

namespace App\Repository;

use App\Entity\Lead;
use App\Traits\HasPagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lead|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lead|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lead[]    findAll()
 * @method Lead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeadRepository extends ServiceEntityRepository
{
    use HasPagination;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lead::class);
    }

    public function findAllLeads(int $page = 1, int $limit = 10, string $sort = 'createdAt' , bool $ascending = true)
    {
        $query = $this->createQueryBuilder('l')
            ->orderBy("l.{$sort}", $ascending ? 'ASC' : 'DESC')
            ->getQuery();
        
        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }
}
