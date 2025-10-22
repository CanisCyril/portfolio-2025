<?php

namespace App\Services;

use App\Contracts\ProductSearchServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class ProductSearchService implements ProductSearchServiceInterface
{
    /**
     * Search for products.
     *
     * @param array $filters
     * @param string $url
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function search(array $filters, $url): LengthAwarePaginator
    {
        $perPage = 10;
        $skip = 0;

        $query = $filters['q'] ?? '';

        // Make HTTP GET request to DummyJSON API, passing query as a parameter
        $response = Http::get($url, [
            'q' => $query,
            'limit' => $perPage,
            'skip' => $skip,
        ]);

        // Handle HTTP errors gracefully
        if (!$response->successful()) {
            // Optionally log the error or throw an exception
            return new LengthAwarePaginator([], 0, $perPage);
        }

        $products = collect($response->json('products') ?? []);
        $total = $response->json('total') ?? $products->count();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            $products,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
