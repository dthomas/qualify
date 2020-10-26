<?php

namespace App\Traits;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

trait HasPagination
{
    private function paginate(Query $dql, int $page = 1, int $limit = 10): Paginator
    {
        $paginator = new Paginator($dql);
        $paginator
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        return $paginator;
    }
}
