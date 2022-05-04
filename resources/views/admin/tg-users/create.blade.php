@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Пользователи</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Админ-панель</li>
                        <li class="breadcrumb-item active">Добавление пользователя</li>
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
                            <h3 class="card-title">Добавление пользователя</h3>

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
                            <form action="{{ route('admin.tg-users.store') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="uid">Telegram ID</label>
                                        <input type="text" class="form-control @error('uid') is-invalid @enderror" id="uid" name="uid" placeholder="Введите Telegram ID">
                                    </div>
                                    <div class="form-group">
                                        <label for="parent">Пригласивший</label>
                                        <select class="form-control select2bs4" name="parent" id="parent">
                                            <option>Выберите пользователя</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->first_name ?? '' }} {{ $user->username ?? '' }} {{ $user->uid }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Добавить</button>
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
