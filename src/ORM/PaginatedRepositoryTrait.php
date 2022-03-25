<?php

namespace Jhg\DoctrinePagination\ORM;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;

trait PaginatedRepositoryTrait
{
    public function findPageBy(int $page, int $rpp, array $criteria = [], array $orderBy = null, int $hydrateMode = AbstractQuery::HYDRATE_OBJECT): PaginatedArrayCollection
    {
        $qb = $this->createPaginatedQueryBuilder($criteria, null, $orderBy);
        $qb->addSelect($this->getEntityAlias());
        $this->processOrderBy($qb, $orderBy);

        // find all
        if ($rpp > 0) {
            $qb->addPagination($page, $rpp);
        }

        $results = $qb->getQuery()->getResult($hydrateMode);

        // count elements if needed
        if ($rpp > 0) {
            $total = count($results) < $rpp && $page == 1 ? count($results) : $this->countBy($criteria);
        } else {
            $total = -1;
        }

        return new PaginatedArrayCollection($results, $page, $rpp, $total);
    }

    public function countBy(array $criteria = []): int
    {
        try {
            $qb = $this->createPaginatedQueryBuilder($criteria);
            $qb->select('COUNT(' . $this->getEntityAlias() . ')');

            return (int)$qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

    protected function createPaginatedQueryBuilder(array $criteria = [], ?string $indexBy = null, ?array $orderBy = null): PaginatedQueryBuilder
    {
        $qb = new PaginatedQueryBuilder($this->_em);
        $qb->from($this->_entityName, $this->getEntityAlias(), $indexBy);

        $this->processCriteria($qb, $criteria);

        return $qb;
    }

    protected function processCriteria(PaginatedQueryBuilder $qb, array $criteria): void
    {
        if ($this instanceof FilterRepositoryInterface) {
            $this->buildFilterCriteria($qb, $criteria);
        } else {
            foreach ($criteria as $field => $value) {
                $fieldParameter = 'f'.substr(md5($field), 0, 5);

                if (is_null($value)) {
                    $qb->andWhere(sprintf('%s.%s IS NULL', $this->getEntityAlias(), $field));
                } elseif (is_array($value)) {
                    $qb->andWhere($qb->expr()->in(sprintf('%s.%s', $this->getEntityAlias(), $field), $value));
                } else {
                    $qb->andWhere(sprintf('%s.%s = :%s', $this->getEntityAlias(), $field, $fieldParameter));
                    $qb->setParameter($fieldParameter, $value);
                }
            }
        }
    }

    protected function processOrderBy(PaginatedQueryBuilder $qb, ?array $orderBy = null): void
    {
        if (is_array($orderBy)) {
            $qb->addOrder($orderBy, $this->getEntityAlias());
        }
    }

    protected function getEntityAlias(): string
    {
        $entityName = explode('\\', $this->_entityName);

        return strtolower(substr(array_pop($entityName), 0, 1));
    }
}