
[![Latest Stable Version](https://poser.pugx.org/javihgil/doctrine-pagination/v/stable.svg)](https://packagist.org/packages/javihgil/doctrine-pagination)
[![Latest Unstable Version](https://poser.pugx.org/javihgil/doctrine-pagination/v/unstable.svg)](https://packagist.org/packages/javihgil/doctrine-pagination)
[![License](https://poser.pugx.org/javihgil/doctrine-pagination/license.svg)](https://packagist.org/packages/javihgil/doctrine-pagination)
[![Total Downloads](https://poser.pugx.org/javihgil/doctrine-pagination/downloads)](https://packagist.org/packages/javihgil/doctrine-pagination)
[![Build status](https://travis-ci.com/javihgil/doctrine-pagination.svg?branch=master)](https://travis-ci.com/javihgil/doctrine-pagination)

This library provides a paginated repository and collection for Doctrine.

# Installation

## Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require javihgil/doctrine-pagination:^1.1@dev
```

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

# Using Paginated Repository

*public* **findPageBy** *($page, $rpp, array $criteria = [], array $orderBy = null)*

Returns a paginated collection of elements that matches criteria.

*public* **countBy** *(array $criteria = [])*

Returns the total number of elements that matches criteria.

*protected* **createPaginatedQueryBuilder** *(array $criteria = [], $indexBy = null)*

This method is used by findPageBy and countBy methods to create a QueryBuilder, and can be used in
 other repository custom methods.

**processCriteria (protected)**

This method is called from createPaginatedQueryBuilder to add criteria conditions.

This can be overridden to customize those criteria conditions.

**findBy and findAll**

PaginatedRepository overrides findBy and findAll default Doctrine Repository methods to provides
 code compatibility.

# Using Paginated Collections

The PaginatedRepository always returns a PaginatedArrayCollection:

```php
// some parameters
$page = 5;
$resultsPerPage = 10;

// get repository
$repository = $doctrine->getRepository('Task');

/** @var PaginatedArrayCollection */
$result = $repository->findPageBy($page, $resultsPerPage, ['field'=>'value']);
```

**count()**

```php
// count obtained results as usual
$pageResults = $result->count(); // 10
```

**getTotal()**

```php
// get total results
$totalResults = $result->getTotal(); // 95
```

**getPage()**

```php
// current page
$currentPage = $result->getPage(); // 5
```

**getRpp()**

```php
// current results per page
$currentResultsPerPage = $result->getRpp(); // 10
```

**getPages()**

```php
// get total pages
$totalPages = $result->getPages(); // 10
```

**getNextPage()**

```php
// get next page number
$nextPage = $result->getNextPage(); // 6
```

**getPrevPage()**

```php
// get prev page number
$prevPage = $result->getPrevPage(); // 4
```

