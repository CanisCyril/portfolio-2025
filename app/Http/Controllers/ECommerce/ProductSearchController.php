<?php

namespace App\Http\Controllers\ECommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contracts\ProductSearchServiceInterface;
use App\Models\Product;
use App\Http\Requests\ECommerce\ProductSearchRequest;
use App\Http\Resources\ECommerce\ProductSearchResource;

class ProductSearchController extends Controller
{
    protected $searchService;

    public function __construct(ProductSearchServiceInterface $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(Request $productSearchRequest)
    {
        // You may need to extract $filters from $productSearchRequest
        $filters = $productSearchRequest->all();

        $url = 'https://dummyjson.com/products/search';

        $products = $this->searchService->search($filters, $url);

        return ProductSearchResource::collection($products);
    }
    
}
