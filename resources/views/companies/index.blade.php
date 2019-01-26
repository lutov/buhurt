@extends('layouts.default')

@section('title'){{$ru_section}}@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center">
        <h1 class="pt-5">@yield('title')</h1>
        <h2 class="pb-3">@yield('subtitle')</h2>
    </section>

    {!! ElementsHelper::getElements($request, $elements, $section, $options) !!}

@stop