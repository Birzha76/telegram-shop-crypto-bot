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
                        <li class="breadcrumb-item active">{{ __('ui.checks') }}</li>
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
                <h3 class="card-title">{{ __('ui.checks_list') }} <span class="badge bg-primary">{{ $checks->total() }}</span></h3>

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
                @if (count($checks))
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            @if( Auth::user()->is_admin)
                            <th>{{ __('ui.checks_list') }}</th>
                            @endif
                            <th>{{ __('ui.status') }}</th>
                            <th>{{ __('ui.request_date') }}</th>
                            <th>{{ __('ui.update_date') }}</th>
                            <th style="width: 100px">{{ __('ui.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($checks as $check)
                            <tr>
                                <td>{{ $check->id }}</td>
                                @if( Auth::user()->is_admin)
                                <td><a href="{{ route('admin.tg-users.edit', $check->user->id) }}">{{ $check->user->uid }}</a></td>
                                @endif
                                <td>{{ \App\Enums\CheckStatus::label($check->status) }}</td>
                                <td>{{ $check->created_at }}</td>
                                <td>{{ $check->updated_at }}</td>
                                <td>
                                    @if( Auth::user()->is_admin && $check->status !== \App\Enums\CheckStatus::Considered)
                                    <a href="{{ route('admin.checks.edit', $check->id) }}" class="btn btn-info btn-sm float-left mr-1"><i class="fas fa-pencil-alt"></i></a>
                                    @endif
                                    <form action="{{ route('admin.checks.destroy', $check->id) }}" method="POST" class="float-left">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('ui.confirm_deletion') }}')"><i class="fas fa-trash-alt"></i></button></form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{ __('ui.no_checks') }}</p>
                @endif

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                {{ $checks->links('vendor.pagination.bootstrap-4') }}
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
