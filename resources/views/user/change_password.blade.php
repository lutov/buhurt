@extends('layouts.default')

@section('title')
	Смена пароля
@stop

@section('subtitle')

@stop

@section('content')

  	<h1>@yield('title')</h1>

	@if(count($errors))
	<div class="error">
  	@foreach ($errors->all() as $error)
		{!! $error !!}<br/>
	@endforeach
    </div>
	@endif

	<div class="full">
		<div class="half">
			{!! Form::open(array('action' => 'UserController@change_password', 'class' => '', 'method' => 'POST')) !!}
			<p><b>Текущий пароль</b>:{!! Form::password('old_password', $attributes = array('id' => 'old_password', 'class' => 'full', 'autocomplete' => 'off')) !!}</p>
			<p><b>Новый пароль</b>:{!! Form::password('new_password', $attributes = array('id' => 'new_password', 'class' => 'full')) !!}</p>
			{!! Form::submit('Сохранить', $attributes = array('id' => 'change_password')) !!}
			{!! Form::close() !!}
		</div>
	</div>

@stop