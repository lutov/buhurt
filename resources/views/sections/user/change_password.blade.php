@extends('layouts.default')
@section('title')Смена пароля@stop
@section('subtitle')@stop
@section('content')
    @if(count($errors->all()))
        <div class="row">
            <div class="col-12 col-md-8 col-lg-6 m-auto pb-4">
                <div class="card border-danger">
                    <div class="card-header text-center">
                        <h3 class="text-danger m-0">Ошибки</h3>
                    </div>
                    <ol class="list-group text-danger">
                        @foreach ($errors->all() as $error)
                            <li class="list-group-item">{!! $error !!}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6 m-auto pb-4">
            <div class="card @include('card.class')" id="registration_block">
                <div class="card-header text-center">
                    <h1 class="card-title m-0">@yield('title')</h1>
                </div>
                <div class="card-body">
                    <form action="/user/change_password/" id="entrance_form" class="entrance_form" method="POST">
                        <p>
                            <label for="old_password">Текущий пароль</label>
                            <input type="password" name="old_password" id="old_password" class="form-control w-100" autocomplete="off" />
                        </p>
                        <p>
                            <label for="new_password">Новый пароль</label>
                            <input type="password" name="new_password" id="new_password" class="form-control w-100" />
                        </p>
                        <input type="submit" value="Сохранить" id="change_password" class="btn btn-success" />
                    </form>
                </div>
                <div class="card-footer small text-muted">
                    @include('widgets.report')
                </div>
            </div>
        </div>
    </div>
@stop
