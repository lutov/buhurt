@extends('layouts.default')

@section('title')
    {{$book}}
@stop

@section('subtitle')
    {{$writers}}
@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>
        @yield('title')
    </h1>

    <p>{{$year}} год | {{$publisher}} | {{$genre}}</p>

    <p>«{{nl2br($book)}}» — случайно сгенерированная книга. На самом деле её нет. А жаль.</p>

@stop