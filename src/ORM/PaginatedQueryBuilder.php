<?php

namespace KaduDutra\DoctrinePagination\ORM;

use Doctrine\ORM\QueryBuilder;

/**
 * Class QueryBuilder
 */
class PaginatedQueryBuilder extends QueryBuilder
{
    /**
     * @param array  $orderBy
     * @param string $entityAlias
     *
     * @return $this
     */
    public function addOrder(array $orderBy, $entityAlias = null)
    {
        foreach ($orderBy as $field => $order) {
            if (preg_match('/^[a-z0-9][a-z0-9\_]+$/i', $field)) {
                $this->addOrderBy(($entityAlias?$entityAlias.'.':'').$field, $order);
            } else {
                $this->addOrderBy($field, $order);
            }
        }

        return $this;
    }

    /**
     * @param int $page
     * @param int $rpp
     *
     * @return $this
     */
    public function addPagination($page, $rpp)
    {
        $offset = ($page - 1) * $rpp;
        $limit = $rpp;

        $this->setFirstResult($offset);
        $this->setMaxResults($limit);

        return $this;
    }
}
