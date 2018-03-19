@extends('layouts.default')

@section('title')
	Ошибка
@stop

@section('subtitle')

@stop

@section('content')

  	<h1>@yield('title')</h1>

	<p>{!! $message; !!}</p>

@stop