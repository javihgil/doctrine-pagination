<?php

namespace DoctrinePagination\ORM;

use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ObjectRepository;
use DoctrinePagination\Collection\PaginatedArrayCollection;

interface PaginatedRepositoryInterface extends ObjectRepository
{
    public function findPageBy(
        ?int $page = 1, ?int $per_page = 20, array $criteria = [], ?array $orderBy = null, ?int $hydrateMode = AbstractQuery::HYDRATE_OBJECT
    ): PaginatedArrayCollection;

    public function countBy(array $criteria = []): int;
}
