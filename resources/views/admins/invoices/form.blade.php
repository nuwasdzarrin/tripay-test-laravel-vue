@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <b>{{ $page == 'create' ? __('Add '.ucfirst($feature_name['singular'])) : __('Edit '.ucfirst($feature_name['singular'])) }}</b>
                    </div>
                    <div class="card-body">
                        <form method="post"
                            action="{{$page == 'create' ? route($feature_name['plural'].'.store') : route($feature_name['plural'].'.update', ['invoice' => $invoice])}}">
                            @csrf
                            @if($page == 'edit')
                                <input type="hidden" name="_method" value="put">
                            @endif
                            @foreach ($fields as $field)
                                <div class="mb-3">
                                    <label for="{{ $field->name }}">{{ $field->label }}</label>
                                    @if ($field->type == 'textarea')
                                        <textarea class="form-control @error($field->name) is-invalid @enderror"
                                            id="{{ $field->name }}" name="{{ $field->name }}"
                                            {{ $field->required ? 'required' : '' }}>
                                            {{ old($field->name, $field->value) }}
                                        </textarea>
                                    @elseif ($field->type == 'select')
                                        <select class="form-select @error($field->name) is-invalid @enderror"
                                            id="{{ $field->name }}" name="{{ $field->name }}">
                                            @foreach($field->options as $option)
                                                <option value="{{$option['key']}}"
                                                    {{ (old($field->name, $field->value) == $option['key']) ? 'selected' : ''}}>
                                                    {{$option['value']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="{{ $field->type }}"
                                            class="form-control @error($field->name) is-invalid @enderror"
                                            id="{{ $field->name }}" name="{{ $field->name }}"
                                            value="{{ old($field->name, $field->value) }}"
                                            {{ $field->required ? 'required' : '' }}>
                                    @endif
                                    @error($field->name)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary btn-sm">
                                {{$page == 'create' ? 'Store' : 'Update'}} {{ucfirst($feature_name['singular'])}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
