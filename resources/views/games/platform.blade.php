@extends('layouts.default')

@section('title')
	{{$platform->name}}
@stop

@section('subtitle')
	{{$ru_section}}
@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

  	<div class="element_additional_info">
    	<p>
    	</p>
    </div>

	{!! Helpers::get_elements($games, $section, $sort_options) !!}

@stop