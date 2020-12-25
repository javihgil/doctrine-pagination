<?php

namespace DoctrinePagination\ORM;

use Doctrine\ORM\QueryBuilder;

interface FilterRepositoryInterface
{
    public function buildFilterCriteria(QueryBuilder $qb, array $criteria): void;
}
