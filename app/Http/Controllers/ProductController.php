<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\IndexRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Services\FormFields\ProductFormField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     * @return View
     */
    public function index(IndexRequest $request): View
    {
        $products = new ProductCollection($this->product->getFiltered($request->validated()));
        $fields = ProductFormField::generateFields();
        return View('admins.products.index', ['data' => $products, 'fields' => $fields]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $fields = ProductFormField::generateFields();
        return View('admins.products.form', ['page' => 'create', 'fields' => $fields]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $product = $this->product->create($request->validated());
        return Redirect::route('products.index')->with('success', 'Successfully create product ' . $product->name);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return View
     */
    public function edit(Product $product): View
    {
        $fields = ProductFormField::generateFields($product);
        return View('admins.products.form', ['page' => 'edit', 'fields' => $fields, 'product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());
        return Redirect::route('products.index')->with('success', 'Successfully update product ' . $product->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return Redirect::route('products.index')->with('success', 'Successfully delete product ' . $product->name);
    }
}
