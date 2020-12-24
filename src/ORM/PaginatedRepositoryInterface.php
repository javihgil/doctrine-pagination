<?php

namespace KaduDutra\DoctrinePagination\ORM;

use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ObjectRepository;
use KaduDutra\DoctrinePagination\Collection\PaginatedArrayCollection;

interface PaginatedRepositoryInterface extends ObjectRepository
{
    public function findPageBy(
        int $page, int $per_page, array $criteria = [], ?array $orderBy = null, ?int $hydrateMode = AbstractQuery::HYDRATE_OBJECT
    ): PaginatedArrayCollection;

    public function countBy(array $criteria = []): int;
}
