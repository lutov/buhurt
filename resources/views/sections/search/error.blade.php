@extends('layouts.default')

@section('title')Ошибка@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center">
        <h1 class="pt-5">@yield('title')</h1>
        <h2 class="pb-3">@yield('subtitle')</h2>
    </section>

    <div class="row">

        <div class="col-md-12">

            <p>{!! $message; !!}</p>

        </div>

    </div>

@stop