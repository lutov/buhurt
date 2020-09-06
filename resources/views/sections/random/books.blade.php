@extends('layouts.default')

@section('title'){{$book}}@stop

@section('subtitle'){{$writers}}@stop

@section('content')

    <section class="text-center">
        <h1 class="mt-5">@yield('title')</h1>
        <h2 class="mb-3">@yield('subtitle')</h2>
    </section>

    <div class="row">

        <div class="col-md-12">

    <p>{{$year}} год | {{$publisher}} | {{$genre}}</p>

    <p>«{{nl2br($book)}}» — случайно сгенерированная книга. На самом деле её нет. А жаль.</p>

        </div>

    </div>

@stop