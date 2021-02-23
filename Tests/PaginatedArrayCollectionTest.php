<?php

namespace Jhg\DoctrinePagination\Tests\Collection;

use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use \Mockery as m;

/**
 * Class PaginatedArrayCollectionTest
 */
class PaginatedArrayCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function collectionProvider()
    {
        return [
            [[], 1, 10, 100, 10, 2, null],
            [[], 10, 10, 100, 10, null, 9],
            [[], 1, 10, 0, 0, 2, null],
            [[], 2, 1, 2, 2, null, 1],
            [[], 1, 1, 2, 2, 2, null],
        ];
    }

    /**
     * @dataProvider collectionProvider
     *
     * @param array $elements
     * @param int   $page
     * @param int   $rpp
     * @param int   $total
     * @param int   $pagesExpected
     * @param int   $nextPageExpected
     * @param int   $prevPageExpected
     */
    public function testCollection($elements, $page, $rpp, $total, $pagesExpected, $nextPageExpected, $prevPageExpected)
    {
        $collection = new PaginatedArrayCollection($elements, $page, $rpp, $total);

        $this->assertEquals($page, $collection->getPage());
        $this->assertEquals($rpp, $collection->getRpp());
        $this->assertEquals($pagesExpected, $collection->getPages());
        $this->assertEquals($nextPageExpected, $collection->getNextPage());
        $this->assertEquals($prevPageExpected, $collection->getPrevPage());
    }
}