@extends('layouts.default')
@section('title')Вход@stop
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
                    <form action="/user/login/" id="entrance_form" class="entrance_form" method="POST">
                        <p><input name="email" placeholder="Логин или e-mail" class="form-control w-100" /></p>
                        <p><input type="password" name="password" placeholder="Пароль" class="form-control w-100" /></p>
                        <input type="submit" value="Войти" class="btn btn-success" />
                        <a class="btn btn-primary"
                           href="https://oauth.vk.com/authorize?client_id=4591194&redirect_uri=https://buhurt.ru/user/vk_auth&scope=email&display=popup">
                            <img src="https://vk.com/favicon.ico" alt="Вконтакте" style="width: 1.2rem;" /> vk.com
                        </a>
                    </form>
                </div>
                <div class="card-footer small text-muted">
                    @include('widgets.report')
                </div>
            </div>
        </div>
    </div>
@stop
