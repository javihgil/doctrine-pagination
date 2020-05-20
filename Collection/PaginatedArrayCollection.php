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
     * @return int
     */
    public function getFirstPage()
    {
        if (0 == $this->getPages()) {
            return null;
        }

        return 1;
    }

    /**
     * @return int
     */
    public function getLastPage()
    {
        if (0 == $this->getPages()) {
            return null;
        }

        return $this->getPages();
    }

    /**
     * @return int|null
     */
    public function getNextPage()
    {
        if (!$this->isLastPage()) {
            return $this->getPage() + 1;
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getPrevPage()
    {
        if (!$this->isFirstPage()) {
            return $this->getPage() - 1;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isFirstPage()
    {
        return $this->getPage() == 1;
    }

    /**
     * @return bool
     */
    public function isLastPage()
    {
        return !$this->getPages() || $this->getPage() == $this->getPages();
    }
}