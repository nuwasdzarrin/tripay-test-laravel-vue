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
    public function index(IndexRequest $request): View
    {
        $invoices = new InvoiceCollection($this->invoice->getFiltered($request->validated(), ['product_name']));
        $fields = InvoiceFormField::generateFields();
        return View('admins.invoices.index', ['data' => $invoices, 'fields' => $fields]);
    }

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
        return Redirect::route('invoices.index')->with('success', 'Successfully create invoice');
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

    public function edit(Invoice $invoice): View
    {
        $products = $this->product->scopeSelectIdAndNameAsKeyValue();
        $fields = InvoiceFormField::generateFields($invoice, ['products' => $products->toArray()]);
        return View('admins.invoices.form', ['feature_name' => $this->feature_name, 'page' => 'edit',
            'fields' => $fields, 'invoice' => $invoice]);
    }

    public function update(UpdateRequest $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update($request->validated());
        return Redirect::route('invoices.index')->with('success', 'Successfully update invoice');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();
        return Redirect::route('invoices.index')->with('success', 'Successfully delete invoice');
    }
}
