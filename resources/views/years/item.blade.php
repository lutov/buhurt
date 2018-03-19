@extends('layouts.default')

@section('title')
	{{$year}}
@stop

@section('subtitle')
	{{$ru_section}}
@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>{{trim($year)}}-й год</h1>

  	<div class="element_additional_info"></div>

	{!! Helpers::get_elements($elements, $section, $sort_options) !!}

@stop