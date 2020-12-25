<?php

namespace DoctrinePagination\tests\Collection;

use DoctrinePagination\Collection\PaginatedArrayCollection;
use PHPUnit\Framework\TestCase;

class PaginatedArrayCollectionTest extends TestCase
{
    public function collectionProvider()
    {
        return [
            [[], 1, 10, 100, 10],
            [[], 10, 10, 100, 10],
            [[], 1, 10, 0, 0],
            [[], 2, 1, 2, 2],
            [[], 1, 1, 2, 2],
        ];
    }

    public function testCollection($elements, $page, $per_page, $total, $pagesExpected)
    {
        $collection = new PaginatedArrayCollection($elements, $page, $per_page, $total);

        $this->assertEquals($page, $collection->getCurrentPage());
        $this->assertEquals($per_page, $collection->getPerPage());
        $this->assertEquals($pagesExpected, $collection->getTotal());
        $this->assertEquals($pagesExpected, $collection->getLastPage());
    }
}
