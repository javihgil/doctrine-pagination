# doctrine-pagination

Pagination for doctrine results

## Configure Repository

**Configure PaginatedRepository for Entity**

**Configure PaginatedRepository globally** 

**Extend PaginatedRepository**

## Usage

    // some parameters
    $page = 5;
    $resultsPerPage = 10;
    
    // get repository
    $repository = $doctrine->getRepository('Entity');
    
    /** @var PaginatedArrayCollection */
    $result = $repository->findPageBy($page, $resultsPerPage, ['field'=>'value']);

    // count obtained results as usual
    $pageResults = $result->count(); // 10
    
    // get total results
    $totalResults = $result->getTotal(); // 95
    
    // current page and rpp
    $currentPage = $result->getPage(); // 5
    $currentResultsPerPage = $result->getRpp(); // 10
    
    // get total pages
    $totalPages = $result->getPages(); // 10

    // get next and prev page numbers
    $nextPage = $result->getNextPage(); // 6
    $prevPage = $result->getPrevPage(); // 4

