<?php

namespace DoctrinePagination\Collection;

use Doctrine\Common\Collections\ArrayCollection;

class PaginatedArrayCollection extends ArrayCollection
{
    protected ?int $total;

    protected ?int $last_page;

    protected ?int $per_page;

    protected ?int $current_page;

    protected ?string $next_page_url;

    protected ?string $prev_page_url;

    public function __construct(array $elements = [], int $current_page = null, int $per_page = 10, int $total = null)
    {
        $this->total = $total;
        $this->per_page = $per_page;
        $this->current_page = $current_page;

        $this->last_page = $this->getLastPage();
        $this->next_page_url = $this->getNextPageUrl();
        $this->prev_page_url = $this->getPrevPageUrl();

        parent::__construct($elements);
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function getLastPage(): ?int
    {
        if (!$this->getPerPage()) {
            throw new \LogicException('ResultsPerPage was not setted');
        }

        if (!$this->getTotal()) {
            return 0;
        }

        $this->last_page = ceil($this->getTotal() / $this->getPerPage());

        return $this->last_page;
    }

    public function getPerPage(): ?int
    {
        return $this->per_page;
    }

    public function getCurrentPage(): ?int
    {
        return $this->current_page;
    }

    public function getNextPageUrl(): ?string
    {
        $this->next_page_url = $this->mountUrl($this->getCurrentPage() + 1);

        return $this->next_page_url;
    }

    public function getPrevPageUrl(): ?string
    {
        $this->prev_page_url = $this->mountUrl($this->getCurrentPage() - 1);

        return $this->prev_page_url;
    }

    private function mountUrl(int $page): string
    {
        if ($page < 1) {
            $page = 1;
        }

        if ($page > $this->getTotal()) {
            $page = $this->getTotal();
        }

        return sprintf("?page=%s&per_page=%s", $page, $this->getPerPage());
    }
}
