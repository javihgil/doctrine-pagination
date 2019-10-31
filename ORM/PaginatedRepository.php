<?php

namespace Jhg\DoctrinePagination\ORM;

use Doctrine\ORM\EntityRepository;

/**
 * Class PaginatedRepository
 */
class PaginatedRepository extends EntityRepository implements PaginatedRepositoryInterface
{
    use PaginatedRepositoryTrait;
    use FilterRepositoryTrait;

    /**
     * {@inheritdoc}
     *
     * @deprecated this method will be removed in this repository
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if ($offset !== null && $limit) {
            $page = ceil($offset/$limit) + 1;
        } else {
            $page = 1;
        }

        if (!$limit) {
            $limit = 100000000;
        }

        return $this->findPageBy($page, $limit, $criteria, $orderBy);
    }
}