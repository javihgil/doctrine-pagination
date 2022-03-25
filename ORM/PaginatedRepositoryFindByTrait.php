<?php

namespace Jhg\DoctrinePagination\ORM;

trait PaginatedRepositoryFindByTrait
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if ($offset !== null && $limit !== null && $limit > 0) {
            $page = ceil($offset/$limit) + 1;
        } else {
            $page = 1;
        }

        return $this->findPageBy($page, $limit !== null && $limit > 0 ? $limit : -1, $criteria, $orderBy);
    }
}