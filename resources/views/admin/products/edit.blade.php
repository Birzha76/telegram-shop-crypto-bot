@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('ui.products') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">{{ __('ui.admin_panel') }}</li>
                        <li class="breadcrumb-item active">{{ __('ui.product_editing') }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('ui.product_editing') }}</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="title">{{ __('ui.name') }}</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="{{ __('ui.enter_product_name') }}" value="{{ $product->title }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="category_id">{{ __('ui.select_category') }}</label>
                                        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id">
                                            <option value="">-</option>
                                            @if(count($categories))
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">{{ __('ui.price') }}</label>
                                        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="{{ __('ui.enter_product_price') }}" value="{{ $product->price }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">{{ __('ui.description') }}</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="{{ __('ui.enter_product_description') }}" cols="30" rows="10">{{ $product->description }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="details">{{ __('ui.product_content') }}</label>
                                        <textarea class="form-control @error('details') is-invalid @enderror" id="details" name="details" placeholder="{{ __('ui.enter_product_content') }}" cols="30" rows="10">{{ $product->details }}</textarea>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">{{ __('ui.save') }}</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">

                        </div>
                        <!-- /.card-footer-->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
@endsection
