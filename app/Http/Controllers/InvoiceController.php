<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\IndexRequest;
use App\Http\Requests\Invoice\StoreRequest;
use App\Http\Requests\Invoice\UpdateRequest;
use App\Http\Resources\InvoiceCollection;
use App\Models\Invoice;
use App\Models\Product;
use App\Services\FormFields\InvoiceFormField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    protected Invoice $invoice;
    protected Product $product;
    protected Array $feature_name;

    public function __construct(Invoice $invoice, Product $product){
        $this->invoice = $invoice;
        $this->product = $product;
        $this->feature_name['singular'] = 'invoice';
        $this->feature_name['plural'] = 'invoices';
    }
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(IndexRequest $request): View
    {
        $invoices = new InvoiceCollection($this->invoice->getFiltered($request->validated()));
        $fields = InvoiceFormField::generateFields();
        return View('admins.invoices.index', ['data' => $invoices, 'fields' => $fields]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $products = $this->product->scopeSelectIdAndNameAsKeyValue();
        $fields = InvoiceFormField::generateFields([], ['products' => $products->toArray()]);
        return View('admins.invoices.form', ['feature_name' => $this->feature_name,'page' => 'create',
            'fields' => $fields]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $product = $this->invoice->create($request->validated());
        return Redirect::route('products.index')->with('success', 'Successfully create product ' . $product->name);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Invoice\UpdateRequest  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
