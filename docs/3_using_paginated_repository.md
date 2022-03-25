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