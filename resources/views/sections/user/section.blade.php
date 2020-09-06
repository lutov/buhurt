@extends('layouts.default')

@section('title')Вход@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center">
        <h1 class="pt-5">@yield('title')</h1>
    <!--h2 class="pb-3">@yield('subtitle')</h2-->
    </section>

    <div class="row">

        <div class="col-md-12">

            <div class="card w-75 p-4 m-auto" id="registration_block">

                @if(count($errors->all()))

                    <section class="text-center">
                        <h3 class="mb-3 text-danger">Ошибки</h3>
                    </section>

                    <div class="card p-4 border-danger mb-4">

                        <ol class="text-danger list-unstyled">
							<?php
							//print_r($errors);

							foreach ($errors->all() as $error) {
								echo '<li>'.$error.'</li>';
							}
							?>
                        </ol>

                    </div>
                @endif

                    {!! Form::open(array('action' => 'User\UserController@login', 'id' => 'entrance_form', 'class' => 'entrance_form', 'method' => 'POST')) !!}

                    <p>{!! Form::text('email', $value = null, $attributes = array('placeholder' => 'Логин или e-mail', 'class' => 'form-control w-100')) !!}</p>
                    <p>{!! Form::password('password', $attributes = array('placeholder' => 'Пароль', 'class' => 'form-control w-100')) !!}</p>
                    <p>{!! Form::submit('Войти', $attributes = array('class' => 'btn btn-success')) !!}</p>

                    {!! Form::close() !!}

            </div>

            <section class="text-center">
                <h3 class="mt-5 mb-3">Другие способы</h3>
            </section>

            <div class="card w-75 p-4 m-auto" id="registration_block">

                <p>
                    <a class="btn btn-primary" href="https://oauth.vk.com/authorize?client_id=4591194&redirect_uri=https://buhurt.ru/user/vk_auth&scope=email&display=popup">
                        <img src="https://vk.com/favicon.ico" alt="Вконтакте" /> vk.com
                    </a>
                </p>

            </div>

            {!! DummyHelper::report('enter') !!}

        </div>

    </div>

@stop