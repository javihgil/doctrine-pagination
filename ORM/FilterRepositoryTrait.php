<?php

namespace Jhg\DoctrinePagination\ORM;

use Doctrine\ORM\QueryBuilder;

trait FilterRepositoryTrait
{
    /**
     * @param QueryBuilder $qb
     * @param array        $criteria
     */
    public function buildFilterCriteria(QueryBuilder $qb, array $criteria): void
    {
        foreach ($criteria as $field => $value) {
            $this->filterField($qb, $field, $value);
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $field
     * @param mixed        $value
     */
    protected function filterField(QueryBuilder $qb, string $field, $value): void
    {
        list ($fieldName, $operatorName) = $this->splitFieldName($field);

        $fieldParameter = 'f'.substr(md5($field), 0, 5);

        switch ($operatorName) {
            case 'like':
                $operator = 'LIKE';
                $value = "%$value%";
                break;

            case 'in':
            case (is_array($value)):
                $qb->andWhere($qb->expr()->in(sprintf('%s.%s', $this->getEntityAlias(), $fieldName), $value));
                return;

            case 'null':
                if ($value) {
                    $qb->andWhere(sprintf('%s.%s IS NULL', $this->getEntityAlias(), $field));
                } else {
                    $qb->andWhere(sprintf('%s.%s IS NOT NULL', $this->getEntityAlias(), $field));
                }
                return;

            case (is_null($value)):
                $qb->andWhere(sprintf('%s.%s IS NULL', $this->getEntityAlias(), $field));
                return;

            default:
                $operator = '=';
        }

        $qb->andWhere(sprintf('%s.%s %s :%s', $this->getEntityAlias(), $fieldName, $operator, $fieldParameter));
        $qb->setParameter($fieldParameter, $value);
    }

    /**
     * @param string $field
     *
     * @return array
     */
    private function splitFieldName(string $field): array
    {
        $parts = explode('_', $field);

        if (sizeof($parts) == 1) {
            return [$parts[0], null];
        }

        if (sizeof($parts) == 2) {
            return $parts;
        }

        $operator = array_pop($parts);

        return [implode('_', $parts), $operator];
    }
}