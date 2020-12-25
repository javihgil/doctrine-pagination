<?php

namespace DoctrinePagination\tests\ORM\Helper;

use DoctrinePagination\ORM\FilterRepositoryTrait;

class FilteredRepositoryExample
{
    use FilterRepositoryTrait;

    /**
     * @var string
     */
    protected $entityAlias;

    /**
     * @return string
     */
    public function getEntityAlias(): string
    {
        return $this->entityAlias;
    }

    /**
     * @param string $entityAlias
     */
    public function setEntityAlias(string $entityAlias): void
    {
        $this->entityAlias = $entityAlias;
    }
}
