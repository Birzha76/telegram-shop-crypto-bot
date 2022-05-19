@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('ui.cash') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">{{ __('ui.admin_panel') }}</li>
                        <li class="breadcrumb-item active">{{ __('ui.cash') }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('ui.cash') }}</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('admin.cash.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="text">{{ __('ui.cash_text') }}</label>
                        <textarea class="form-control @error('text') is-invalid @enderror" id="text" name="text" placeholder="{{ __('ui.cash_enter_text') }}" cols="30" rows="10" maxlength="1024">{{ $data['text'] }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">{{ __('ui.cash_img') }}</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="img" name="img">
                                <label class="custom-file-label" for="img">{{ __('ui.cash_enter_img') }}</label>
                            </div>
                        </div>
                        <div>{{ $data['img'] }}</div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('ui.save') }}</button>
                </div>
            </form>
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
