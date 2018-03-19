@extends('layouts.default')

@section('title')
	{{$country->name}}
@stop

@section('subtitle')
	{{$ru_section}}
@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

  	<div class="book_additional_info">
    	<p>
    	</p>
    </div>

	{!! Helpers::get_elements($films, $section, $sort_options, true, true) !!}

@stop