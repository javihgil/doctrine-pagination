<?php

namespace KaduDutra\DoctrinePagination\ORM;

use Doctrine\ORM\EntityRepository;

/**
 * Class PaginatedRepository
 */
class PaginatedRepository extends EntityRepository implements PaginatedRepositoryInterface
{
    use PaginatedRepositoryTrait;
    use PaginatedRepositoryFindByTrait;
    use FilterRepositoryTrait;
}
