<?php

namespace App\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface ProductSearchServiceInterface
{
    /**
     * Search for products based on the provided filters.
     *
     * @param array $filters
     * @return ArrayCollection|LengthAwarePaginator
     */
    public function search(array $filters, $url): LengthAwarePaginator;
}
