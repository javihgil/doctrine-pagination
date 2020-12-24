<?php

namespace KaduDutra\DoctrinePagination\Collection;

use Doctrine\Common\Collections\ArrayCollection;

class PaginatedArrayCollection extends ArrayCollection
{
    protected ?int $page;

    protected ?int $resultsPerPage;

    protected ?int $total;

    public function __construct(array $elements = [], int $page = null, int $resultsPerPage = 10, int $total = null)
    {
        $this->resultsPerPage = $resultsPerPage;
        $this->total = $total;
        $this->page = $page;

        parent::__construct($elements);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPages(): int
    {
        if (!$this->getResultsPerPage()) {
            throw new \LogicException('ResultsPerPage was not setted');
        }

        if (!$this->getTotal()) {
            return 0;
        }

        return ceil($this->total / $this->resultsPerPage);
    }

    public function getFirstPage(): ?int
    {
        if (0 == $this->getPages()) {
            return null;
        }

        return 1;
    }

    public function getLastPage(): ?int
    {
        if (0 == $this->getPages()) {
            return null;
        }

        return $this->getPages();
    }

    public function getNextPage(): ?int
    {
        if (!$this->isLastPage()) {
            return $this->getPage() + 1;
        }

        return null;
    }

    public function getPrevPage(): ?int
    {
        if (!$this->isFirstPage()) {
            return $this->getPage() - 1;
        }

        return null;
    }

    public function isFirstPage(): bool
    {
        return $this->getPage() == 1;
    }

    public function isLastPage(): bool
    {
        return !$this->getPages() || $this->getPage() == $this->getPages();
    }
}
