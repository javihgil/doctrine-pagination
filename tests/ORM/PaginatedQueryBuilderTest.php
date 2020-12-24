<?php

namespace KaduDutra\DoctrinePagination\Tests\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use KaduDutra\DoctrinePagination\ORM\PaginatedQueryBuilder;
use PHPUnit\Framework\TestCase;

class PaginatedQueryBuilderTest extends TestCase
{
    public function testAddPaginationFirstPage()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $qb = new PaginatedQueryBuilder($em);
        $qb->addPagination(1, 10);
        $qb->select('t');
        $qb->from('test', 't');

        $this->assertEquals(0, $qb->getFirstResult());
        $this->assertEquals(10, $qb->getMaxResults());
    }

    public function testAddPaginationOtherPage()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $qb = new PaginatedQueryBuilder($em);
        $qb->addPagination(5, 10);
        $qb->select('t');
        $qb->from('test', 't');

        $this->assertEquals(40, $qb->getFirstResult());
        $this->assertEquals(10, $qb->getMaxResults());
    }

    public function testAddOrderBy()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $qb = new PaginatedQueryBuilder($em);
        $qb->addOrder(['first' => 'asc', 'second' => 'desc']);
        $qb->select('t');
        $qb->from('test', 't');
        $this->assertEquals('SELECT t FROM test t ORDER BY first asc, second desc', $qb->getDQL());

        $qb = new PaginatedQueryBuilder($em);
        $qb->addOrder(['t.field' => 'asc']);
        $qb->select('t');
        $qb->from('test', 't');
        $this->assertEquals('SELECT t FROM test t ORDER BY t.field asc', $qb->getDQL());
    }
}
