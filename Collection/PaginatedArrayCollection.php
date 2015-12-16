<?php

namespace Jhg\DoctrinePagination\Collection;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PaginatedArrayCollection
 */
class PaginatedArrayCollection extends ArrayCollection
{
    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $rpp;

    /**
     * @var int
     */
    protected $total;

    /**
     * @param array $elements
     * @param null  $page
     * @param int   $rpp
     * @param null  $total
     */
    public function __construct(array $elements = [], $page = null, $rpp = 10, $total = null)
    {
        $this->page = $page;
        $this->rpp = $rpp;
        $this->total = $total;

        parent::__construct($elements);
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getRpp()
    {
        return $this->rpp;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return int|0
     */
    public function getPages()
    {
        if (!$this->getRpp()) {
            throw new \LogicException('Rpp was not setted');
        }

        if (!$this->getTotal()) {
            return 0;
        }

        return ceil($this->total / $this->rpp);
    }

    /**
     * @return int|null
     */
    public function getNextPage()
    {
        if ($this->getPage() < $this->getPages()) {
            return $this->getPage() + 1;
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getPrevPage()
    {
        if ($this->getPage() > 1) {
            return $this->getPage() - 1;
        }

        return null;
    }
}