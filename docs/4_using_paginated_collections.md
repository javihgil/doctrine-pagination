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

