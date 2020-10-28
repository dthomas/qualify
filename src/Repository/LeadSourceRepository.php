<?php

namespace App\Repository;

use App\Entity\LeadSource;
use App\Traits\HasPagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeadSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeadSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeadSource[]    findAll()
 * @method LeadSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeadSourceRepository extends ServiceEntityRepository
{
    use HasPagination;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeadSource::class);
    }

    public function findAllLeadSources(int $page = 1, int $limit = 10, string $sort = 'createdAt' , bool $ascending = true)
    {
        $query = $this->createQueryBuilder('ls')
            ->orderBy("ls.{$sort}", $ascending ? 'ASC' : 'DESC')
            ->getQuery();
        
        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }
}
