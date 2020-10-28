<?php

namespace App\Repository;

use App\Entity\Product;
use App\Traits\HasPagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    use HasPagination;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllProducts(int $page = 1, int $limit = 10, string $sort = 'createdAt' , bool $ascending = true)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy("p.{$sort}", 'DESC')
            ->orderBy("p.{$sort}", $ascending ? 'ASC' : 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }
}
