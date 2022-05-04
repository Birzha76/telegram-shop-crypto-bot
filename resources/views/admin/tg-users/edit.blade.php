@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Пользователи бота</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Админ-панель</li>
                        <li class="breadcrumb-item active">Редактирование пользователя</li>
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
                            <h3 class="card-title">Редактирование пользователя</h3>

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
                                        <label for="username">Логин</label>
                                        <p><span>{{ $user->username ?? '-' }}</span></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name">Имя пользователя</label>
                                        <p><span>{{ $user->first_name ?? '' }}</span></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="balance">Баланс</label>
                                        <input type="text" class="form-control @error('balance') is-invalid @enderror" id="balance" name="balance" placeholder="Укажите баланс пользователя" value="{{ $user->balance ?? 0 }}">
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
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
