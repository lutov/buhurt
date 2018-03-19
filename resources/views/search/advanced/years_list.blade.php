@extends('layouts.default')

@section('title')
    {{$title}}
@stop

@section('subtitle')
    {{$subtitle}}
@stop

@section('content')

  	<h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

    {!! Helpers::get_list($elements, $section, $sub_section, array(), false) !!}

@stop