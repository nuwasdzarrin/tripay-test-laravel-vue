<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\IndexRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    protected Product $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index(IndexRequest $request) {
        $products = new ProductCollection($this->product->getFiltered($request->validated()));
        return View('shops.index', ['data' => $products]);
    }
}
