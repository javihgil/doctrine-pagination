<?php

namespace Jhg\DoctrinePagination\ORM;

use Doctrine\ORM\EntityRepository;

class PaginatedRepository extends EntityRepository implements PaginatedRepositoryInterface, FilterRepositoryInterface
{
    use PaginatedRepositoryTrait;
    use PaginatedRepositoryFindByTrait;
    use FilterRepositoryTrait;
}