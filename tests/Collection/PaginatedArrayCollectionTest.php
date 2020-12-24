<?php

namespace KaduDutra\DoctrinePagination\Tests\Collection;

use KaduDutra\DoctrinePagination\Collection\PaginatedArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class PaginatedArrayCollectionTest
 */
class PaginatedArrayCollectionTest extends TestCase
{
    /**
     * @return array
     */
    public function collectionProvider()
    {
        return [
            [[], 1, 10, 100, 10, 2, null],
            [[], 10, 10, 100, 10, null, 9],
            [[], 1, 10, 0, 0, null, null],
            [[], 2, 1, 2, 2, null, 1],
            [[], 1, 1, 2, 2, 2, null],
        ];
    }

    /**
     * @dataProvider collectionProvider
     *
     * @param array $elements
     * @param int   $page
     * @param int   $per_page
     * @param int   $total
     * @param int   $pagesExpected
     * @param int   $nextPageExpected
     * @param int   $prevPageExpected
     */
    public function testCollection($elements, $page, $per_page, $total, $pagesExpected, $nextPageExpected, $prevPageExpected)
    {
        $collection = new PaginatedArrayCollection($elements, $page, $per_page, $total);

        $this->assertEquals($page, $collection->getPage());
        $this->assertEquals($per_page, $collection->getResultsPerPage());
        $this->assertEquals($pagesExpected, $collection->getPages());
        $this->assertEquals($nextPageExpected, $collection->getNextPage());
        $this->assertEquals($prevPageExpected, $collection->getPrevPage());
        $this->assertEquals($total ? 1 : null, $collection->getFirstPage());
        $this->assertEquals($pagesExpected, $collection->getLastPage());
    }
}
