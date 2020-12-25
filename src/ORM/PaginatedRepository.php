<?php

namespace DoctrinePagination\ORM;

use Doctrine\ORM\EntityRepository;

class PaginatedRepository extends EntityRepository implements PaginatedRepositoryInterface
{
    use PaginatedRepositoryTrait;
    use PaginatedRepositoryFindByTrait;
    use FilterRepositoryTrait;
}
