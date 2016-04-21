<?php

namespace Jhg\DoctrinePagination\ORM;

use Doctrine\ORM\EntityRepository;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;

/**
 * Class PaginatedRepository
 */
class PaginatedRepository extends EntityRepository
{
    /**
     * {@inheritdoc}
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

    /**
     * @param int   $page
     * @param int   $rpp
     * @param array $criteria
     * @param array $orderBy
     *
     * @return array
     */
    public function findPageBy($page, $rpp, array $criteria = [], array $orderBy = null)
    {
        $qb = $this->createPaginatedQueryBuilder($criteria);
        $qb->addSelect($this->getEntityAlias());
        if (is_array($orderBy)) {
            $qb->addOrder($orderBy, $this->getEntityAlias());
        }
        $qb->addPagination($page, $rpp);

        $results = $qb->getQuery()->getResult();
        $total = $this->countBy($criteria);

        return new PaginatedArrayCollection($results, $page, $rpp, $total);
    }

    /**
     * @param array $criteria
     * @return int
     */
    public function countBy(array $criteria = [])
    {
        $qb = $this->createPaginatedQueryBuilder($criteria);
        $qb->addSelect('COUNT('.$this->getEntityAlias().')');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Creates a query builder for pagination
     *
     * @param array  $criteria
     * @param string $indexBy
     *
     * @return PaginatedQueryBuilder
     */
    protected function createPaginatedQueryBuilder(array $criteria = [], $indexBy = null)
    {
        $qb = new PaginatedQueryBuilder($this->_em);
        $qb->from($this->_entityName, $this->getEntityAlias(), $indexBy);

        $this->processCriteria($qb, $criteria);

        return $qb;
    }

    /**
     * @param PaginatedQueryBuilder $qb
     * @param array        $criteria
     */
    protected function processCriteria(PaginatedQueryBuilder $qb, array $criteria)
    {
        foreach ($criteria as $field => $value) {
            $fieldParameter = 'f'.substr(md5($field), 0, 5);

            if (is_null($value)) {
                $qb->andWhere(sprintf('%s.%s IS NULL', $this->getEntityAlias(), $field));
            } else {
                $qb->andWhere(sprintf('%s.%s = :%s', $this->getEntityAlias(), $field, $fieldParameter));
                $qb->setParameter($fieldParameter, $value);
            }
        }
    }

    /**
     * @return string
     */
    protected function getEntityAlias()
    {
        $entityName = explode('\\', $this->_entityName);

        return strtolower(substr(array_pop($entityName), 0, 1));
    }
}