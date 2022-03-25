<?php

namespace Jhg\DoctrinePagination\ORM;

use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\AbstractQuery;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;

interface PaginatedRepositoryInterface extends ObjectRepository
{
    public function findPageBy(int $page, int $rpp, array $criteria = [], array $orderBy = null, int $hydrateMode = AbstractQuery::HYDRATE_OBJECT): PaginatedArrayCollection;

    public function countBy(array $criteria = []): int;
}