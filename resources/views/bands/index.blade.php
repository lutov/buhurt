@extends('layouts.default')

@section('title')
	Группы
@stop

@section('subtitle')

@stop

@section('content')

  	<h1>@yield('title')</h1>
    <h2>@yield('subtitle')</h2>

    {!! Helpers::get_elements($bands, $section, $sort_options, true, true) !!}

@stop