<?php

namespace DoctrinePagination\Collection;

use Doctrine\Common\Collections\ArrayCollection;

class PaginatedArrayCollection
{
    protected ?int $total;

    protected ?int $last_page;

    protected ?int $per_page;

    protected ?int $current_page;

    protected ?string $next_page_url;

    protected ?string $prev_page_url;

    protected ?array $criteria = [];

    protected ?array $orderBy = [];

    protected ?ArrayCollection $data = null;

    public function __construct(
        array $elements = [],
        int $current_page = null,
        int $per_page = 10,
        int $total = null,
        ?array $criteria = [],
        ?array $orderBy = []
    )
    {
        $this->data = new ArrayCollection($elements);

        $this->total = $total;
        $this->per_page = $per_page;
        $this->current_page = $current_page;
        $this->criteria = $criteria;
        $this->orderBy = $orderBy;

        $this->last_page = $this->getLastPage();
        $this->next_page_url = $this->getNextPageUrl();
        $this->prev_page_url = $this->getPrevPageUrl();

        $this->criteria = null;
        $this->orderBy = null;
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

    public function getCriteria(): ?array
    {
        return $this->criteria;
    }

    public function setCriteria(?array $criteria): PaginatedArrayCollection
    {
        $this->criteria = $criteria;
        return $this;
    }

    public function getOrderBy(): ?array
    {
        return $this->orderBy;
    }

    public function setOrderBy(?array $orderBy): PaginatedArrayCollection
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    private function mountUrl(int $page): string
    {
        $order = '';
        $criteria = '';

        if ($page < 1) {
            $page = 1;
        }

        if ($page > $this->getTotal()) {
            $page = $this->getTotal();
        }

        if (!empty($this->criteria)) {
            foreach ($this->criteria as $key => $data) {
                // @TODO se precisar enviar idcompany como atributo será necessário remover
                if ($key === "idcompany") {
                    continue;
                }
                $criteria .= sprintf("&search=%s&search_field=%s", $data[1] ?? $data, $key);
            }
        }

        if (!empty($this->orderBy)) {
            foreach ($this->orderBy as $key => $data) {
                // @TODO se precisar enviar idcompany como atributo será necessário remover
                if ($key === "idcompany") {
                    continue;
                }
                $order .= sprintf("&sort=%s&order=%s", $key, $data);
            }
        }

        return sprintf("?page=%s&per_page=%s%s%s", $page, $this->getPerPage(), $order, $criteria);
    }
}
