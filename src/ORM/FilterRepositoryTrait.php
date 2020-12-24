<?php

namespace KaduDutra\DoctrinePagination\ORM;

use Doctrine\ORM\QueryBuilder;

trait FilterRepositoryTrait
{
    public function buildFilterCriteria(QueryBuilder $qb, array $criteria): void
    {
        foreach ($criteria as $field => $value) {
            $this->filterField($qb, $field, $value);
        }
    }

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

            default:
                $fieldName = $field;
                $operator = '=';
        }

        $qb->andWhere(sprintf('%s.%s %s :%s', $this->getEntityAlias(), $fieldName, $operator, $fieldParameter));
        $qb->setParameter($fieldParameter, $value);
    }

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
