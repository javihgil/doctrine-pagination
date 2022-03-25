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
                $qb->andWhere($qb->expr()->in(sprintf('%s.%s', $this->getEntityAlias(), $fieldName), is_array($value) ? $value : [$value]));
                return;

            case 'between':
                $value0 = $value[0];
                $value1 = $value[1];
                $value0 = $value0 instanceof \DateTime ? "'".$value0->format('Y-m-d')."'" : $value0;
                $value1 = $value1 instanceof \DateTime ? "'".$value1->format('Y-m-d')."'" : $value1;
                $qb->andWhere($qb->expr()->between(sprintf('%s.%s', $this->getEntityAlias(), $fieldName), $value0, $value1));
                return;

            case 'lt':
            case 'lte':
            case 'gt':
            case 'gte':
                $value = $value instanceof \DateTime ? "'".$value->format('Y-m-d')."'" : $value;
                $qb->andWhere($qb->expr()->$operatorName(sprintf('%s.%s', $this->getEntityAlias(), $fieldName), $value));
                return;

            case 'null':
                if ($value) {
                    $qb->andWhere(sprintf('%s.%s IS NULL', $this->getEntityAlias(), $fieldName));
                } else {
                    $qb->andWhere(sprintf('%s.%s IS NOT NULL', $this->getEntityAlias(), $fieldName));
                }
                return;

            case 'is':
                if ($value === null || $value === 'null') {
                    $qb->andWhere(sprintf('%s.%s IS NULL', $this->getEntityAlias(), $fieldName));
                } elseif ($value === 'not_null') {
                    $qb->andWhere(sprintf('%s.%s IS NOT NULL', $this->getEntityAlias(), $fieldName));
                } else {
                    // not yet implemented
                }
                return;

            default:
                $fieldName = $field;
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