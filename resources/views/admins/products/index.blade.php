@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <b>{{ __('List Product') }}</b>
                        <a href="{{route('products.create')}}" class="btn btn-primary btn-sm">Add Product</a>
                    </div>
                    <div class="card-body">
                        @if(count($data))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    @foreach ($fields as $field)
                                    <th scope="col">{{$field->label}}</th>
                                    @endforeach
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $datum)
                                    <tr>
                                        <th scope="row">{{$key+1}}</th>
                                        @foreach ($fields as $field)
                                        <td>{{ $datum->{$field->name} }}</td>
                                        @endforeach
                                        <td>
                                            <form id="destroy-product-{{$datum->id}}" method="post"
                                                action="{{route('products.destroy', ['product' => $datum])}}"
                                                onsubmit="return confirm('Are you sure to delete product {{ $datum->name }}?')">
                                                @csrf
                                                <input type="hidden" name="_method" value="delete">
                                            </form>
                                            <a href="{{route('products.edit', ['product' => $datum])}}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="submit" form="destroy-product-{{$datum->id}}"
                                                class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <div class="text-center my-4">
                                <p class="h5 mb-4">Your product is empty</p>
                                <a href="{{route('products.create')}}" class="btn btn-primary btn-sm">Add Product</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
