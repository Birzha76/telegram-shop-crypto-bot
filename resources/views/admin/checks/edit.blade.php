@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('ui.checks') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">{{ __('ui.admin_panel') }}</li>
                        <li class="breadcrumb-item active">{{ __('ui.check_editing') }}</li>
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
                            <h3 class="card-title">{{ __('ui.check_editing') }}</h3>

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
                            <form action="{{ route('admin.checks.update', $check->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Telegram ID</label>
                                        <div>
                                            <a href="{{ route('admin.tg-users.edit', $check->user->id) }}">{{ $check->user->uid }}</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('ui.check') }}</label>
                                        <div><img src="{{ \Illuminate\Support\Facades\Storage::url($check->file_path) }}" ></div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('ui.request_date') }}</label>
                                        <input type="text" class="form-control" value="{{ $check->created_at }}" disabled="">
                                    </div>
                                    <div class="form-group">
                                        <label for="percent">{{ __('ui.status') }}?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="status1" value="{{ \App\Enums\CheckStatus::UnderConsideration }}" {{ $check->status == \App\Enums\CheckStatus::UnderConsideration ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status1">
                                                {{ \App\Enums\CheckStatus::label(\App\Enums\CheckStatus::UnderConsideration) }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="status0" value="{{ \App\Enums\CheckStatus::Considered }}" {{ $check->status == \App\Enums\CheckStatus::Considered ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status0">
                                                {{ \App\Enums\CheckStatus::label(\App\Enums\CheckStatus::Considered) }}
                                            </label>
                                        </div>
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
