@extends('layouts.default')

@section('title')
    {{$ru_section}}
@stop

@section('subtitle')

@stop

@section('content')

    <h1>@yield('title')</h1>
    <h2>@yield('subtitle')</h2>

    {{Helpers::get_elements($elements, 'books', $sort_options, true, true)}}

@stop