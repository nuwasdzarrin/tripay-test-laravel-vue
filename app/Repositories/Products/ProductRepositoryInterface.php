<?php

namespace App\Repositories\Products;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function getProductByIdCollection($productId);
    public function create(array $data): Product;
}
