<?php

namespace KaduDutra\DoctrinePagination\ORM;

use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\AbstractQuery;
use KaduDutra\DoctrinePagination\Collection\PaginatedArrayCollection;

interface PaginatedRepositoryInterface extends ObjectRepository
{
    /**
     * @param int        $page
     * @param int        $rpp
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int        $hydrateMode
     *
     * @return PaginatedArrayCollection
     */
    public function findPageBy(int $page, int $rpp, array $criteria = [], array $orderBy = null, $hydrateMode = AbstractQuery::HYDRATE_OBJECT): PaginatedArrayCollection;

    /**
     * @param array $criteria
     *
     * @return int
     */
    public function countBy(array $criteria = []): int;
}
