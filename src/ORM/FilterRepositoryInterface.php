<?php

namespace KaduDutra\DoctrinePagination\ORM;

use Doctrine\ORM\QueryBuilder;

interface FilterRepositoryInterface
{
    /**
     * @param QueryBuilder $qb
     * @param array        $criteria
     */
    public function buildFilterCriteria(QueryBuilder $qb, array $criteria): void;
}
