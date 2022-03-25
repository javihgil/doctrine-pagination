# Configure Repository

## Use it as Entity repository

Configure PaginatedRepository in your entity:

```php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="Jhg\DoctrinePagination\ORM\PaginatedRepository")
 */
class Task
{

}
```

## Create your custom Paginated repository

Create custom repository extending PaginatedRepository:

```php
namespace Repository;

use Jhg\DoctrinePagination\ORM\PaginatedQueryBuilder;
use Jhg\DoctrinePagination\ORM\PaginatedRepository;

/**
 * Class TaskRepository
 */
class TaskRepository extends PaginatedRepository
{

}
```

Configure your Entity:

```php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="Repository\TaskRepository")
 */
class Task
{

}
```

If needed, override processCriteria method in your custom repository to add some custom actions:

```php
/**
 * {@inheritdoc}
 */
protected function processCriteria(PaginatedQueryBuilder $qb, array $criteria)
{
    foreach ($criteria as $field => $value) {
        switch ($field) {
            case 'description':
                $qb->andWhere(...);
                unset($criteria[$field]);
                break;
        }
    }

    parent::processCriteria($qb, $criteria);
}
```
