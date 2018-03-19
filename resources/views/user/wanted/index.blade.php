@extends('layouts.default')

@section('title')
	{{$user->username}}
@stop

@section('subtitle')
	Список желаемого
@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

	{!! Helpers::get_elements($elements, $section, $sort_options, true, true) !!}

@stop