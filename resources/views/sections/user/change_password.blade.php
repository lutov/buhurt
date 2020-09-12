@extends('layouts.default')

@section('title')Смена пароля@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center mt-5 mb-3">
        <h1 class="">@yield('title')</h1>
    </section>

    <div class="row">

        <div class="col-md-12">

            <div class="card w-75 p-4 m-auto">

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

                {!! Form::open(array('action' => 'User\UserController@change_password', 'class' => '', 'method' => 'POST')) !!}
                <p>
                    <label for="old_password">Текущий пароль</label>
                    {!! Form::password('old_password', $attributes = array('id' => 'old_password', 'class' => 'form-control w-100', 'autocomplete' => 'off')) !!}
                </p>

                <p>
                    <label for="new_password">Новый пароль</label>
                    {!! Form::password('new_password', $attributes = array('id' => 'new_password', 'class' => 'form-control w-100')) !!}
                </p>
                {!! Form::submit('Сохранить', $attributes = array('id' => 'change_password')) !!}
                {!! Form::close() !!}

            </div>

            {!! DummyHelper::report('password') !!}

        </div>

    </div>

@stop