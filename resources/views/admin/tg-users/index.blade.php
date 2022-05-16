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
                        <li class="breadcrumb-item active">{{ __('ui.bot_users') }}</li>
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
                <h3 class="card-title">{{ __('ui.list_bot_users') }} <span class="badge bg-primary">{{ $users->total() }}</span></h3>

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
                @if (count($users))
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Telegram ID</th>
                            <th>{{ __('ui.name_login') }}</th>
                            <th>{{ __('ui.balance_btc') }}</th>
                            <th>{{ __('ui.balance_ltc') }}</th>
                            <th style="width: 100px">{{ __('ui.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->uid }}</td>
                                <td>{{ $user->first_name ?? '' }} {{ !empty($user->username) ? '@' . $user->username : '' }}</td>
                                <td>{{ $user->balance_btc }}</td>
                                <td>{{ $user->balance_ltc }}</td>
                                <td>
                                    <a href="{{ route('admin.tg-users.edit', $user->id) }}" class="btn btn-info btn-sm float-left mr-1"><i class="fas fa-pencil-alt"></i></a>
                                    <form action="{{ route('admin.tg-users.destroy', $user->id) }}" method="POST" class="float-left">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('ui.confirm_deletion') }}')"><i class="fas fa-trash-alt"></i></button></form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{ __('ui.no_users') }}</p>
                @endif
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                {{ $users->links('vendor.pagination.bootstrap-4') }}
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
