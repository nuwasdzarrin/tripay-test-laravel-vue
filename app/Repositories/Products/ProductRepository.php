<?php

namespace App\Repositories\Products;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getProductByIdCollection($productId)
    {
        return Product::query()
            ->where('id', $productId)
            ->get();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }
}
