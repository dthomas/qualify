<?php

namespace App\Repository;

use App\Entity\LeadStage;
use App\Traits\HasPagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeadStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeadStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeadStage[]    findAll()
 * @method LeadStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeadStageRepository extends ServiceEntityRepository
{
    use HasPagination;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeadStage::class);
    }

    public function findAllLeadStages(int $page = 1, int $limit = 10, string $sort = 'createdAt' , bool $ascending = true)
    {
        $query = $this->createQueryBuilder('ls')
            ->orderBy("ls.{$sort}", $ascending ? 'ASC' : 'DESC')
            ->getQuery();
        
        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }
}
