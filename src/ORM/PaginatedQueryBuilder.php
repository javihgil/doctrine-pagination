<?php

namespace KaduDutra\DoctrinePagination\ORM;

use Doctrine\ORM\QueryBuilder;

class PaginatedQueryBuilder extends QueryBuilder
{
    public function addOrder(array $orderBy, $entityAlias = null): PaginatedQueryBuilder
    {
        foreach ($orderBy as $field => $order) {
            if (preg_match('/^[a-z0-9][a-z0-9\_]+$/i', $field)) {
                $this->addOrderBy(($entityAlias ? $entityAlias . '.' : '') . $field, $order);
            } else {
                $this->addOrderBy($field, $order);
            }
        }

        return $this;
    }

    public function addPagination(int $page, int $resultsPerPage): PaginatedQueryBuilder
    {
        $offset = ($page - 1) * $resultsPerPage;
        $limit = $resultsPerPage;

        $this->setFirstResult($offset);
        $this->setMaxResults($limit);

        return $this;
    }
}
