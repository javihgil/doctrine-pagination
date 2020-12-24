<?php

namespace KaduDutra\DoctrinePagination\Tests\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Parameter;
use KaduDutra\DoctrinePagination\ORM\PaginatedQueryBuilder;
use KaduDutra\DoctrinePagination\Tests\ORM\Helper\FilteredRepositoryExample;
use PHPUnit\Framework\TestCase;

class FilterRepositoryTraitTest extends TestCase
{
    /**
     * @return array
     */
    public function collectionProvider()
    {
        return [
            [['name_like' => 'test'], 'SELECT t FROM test t WHERE t.name LIKE "%test%"'],
            [['status_in' => ['1', '2']], 'SELECT t FROM test t WHERE t.status IN(\'1\', \'2\')' ],
            [['field_null' => true], 'SELECT t FROM test t WHERE t.field IS NULL'],
            [['field_null' => false], 'SELECT t FROM test t WHERE t.field IS NOT NULL'],
            [['age_lt' => 1], 'SELECT t FROM test t WHERE t.age < 1'],
            [['age_lte' => 1], 'SELECT t FROM test t WHERE t.age <= 1'],
            [['age_gt' => 2], 'SELECT t FROM test t WHERE t.age > 2'],
            [['age_gte' => 2], 'SELECT t FROM test t WHERE t.age >= 2'],
            [['field_with_underscores_like' => 'test'], 'SELECT t FROM test t WHERE t.field_with_underscores LIKE "%test%"'],
            [['raw' => 'value'], 'SELECT t FROM test t WHERE t.raw = "value"' ],
            [['raw_with_underscores' => 'test'], 'SELECT t FROM test t WHERE t.raw_with_underscores = "test"'],
        ];
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testAddPaginationFirstPage(array $filters, string $expectedDql)
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getExpressionBuilder')->willReturn(new Expr());

        $qb = new PaginatedQueryBuilder($em);
        $qb->select('t');
        $qb->from('test', 't');

        $trait = new FilteredRepositoryExample();
        $trait->setEntityAlias('t');
        $trait->buildFilterCriteria($qb, $filters);

        $params = $qb->getParameters();
        $dql = $qb->getDQL();

        /** @var Parameter $param */
        foreach ($params as $param) {
            $dql = str_ireplace(':'.$param->getName(), '"'. $param->getValue() . '"', $dql);
        }

        $this->assertEquals($expectedDql, $dql);
    }
}
