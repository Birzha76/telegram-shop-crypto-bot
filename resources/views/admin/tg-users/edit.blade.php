@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('ui.users') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">{{ __('ui.admin_panel') }}</li>
                        <li class="breadcrumb-item active">{{ __('ui.user_editing') }}</li>
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
                            <h3 class="card-title">{{ __('ui.user_editing') }}</h3>

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
                            <form action="{{ route('admin.tg-users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Telegram ID</label>
                                        <p><span>{{ $user->uid }}</span></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">{{ __('ui.username') }}</label>
                                        <p><span>{{ $user->username ?? '-' }}</span></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name">{{ __('ui.name') }}</label>
                                        <p><span>{{ $user->first_name ?? '' }}</span></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="balance_btc">{{ __('ui.balance_btc') }}</label>
                                        <input type="text" class="form-control @error('balance_btc') is-invalid @enderror" id="balance_btc" name="balance_btc" placeholder="{{ __('ui.specify_balance_btc') }}" value="{{ $user->balance_btc ?? 0 }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="balance_ltc">{{ __('ui.balance_ltc') }}</label>
                                        <input type="text" class="form-control @error('balance_ltc') is-invalid @enderror" id="balance_ltc" name="balance_ltc" placeholder="{{ __('ui.specify_balance_ltc') }}" value="{{ $user->balance_ltc ?? 0 }}">
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
