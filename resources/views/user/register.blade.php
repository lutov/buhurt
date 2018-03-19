@extends('layouts.default')

@section('title')
	Регистрация
@stop

@section('subtitle')

@stop

@section('content')

  	<h1>@yield('title')</h1>

    <div id="reg_error">

        <p class="left"><b>Ошибки</b></p>

        <ol class="left">
            <?php
            //print_r($errors);

            foreach ($errors->all() as $error)
            {
                echo '<li>'.$error.'</li>';
            }
            ?>
        </ol>

    </div>

	<script>
		$(document).ready(function(){

            var reg_error = $('#reg_error');
            //console.log(reg_error);

            var registration_block = $('#registration_block');
            //console.log(entrance_block);

            show_registration();
            registration_block.prepend(reg_error);

        });
	</script>

@stop