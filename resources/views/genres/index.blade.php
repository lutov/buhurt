@extends('layouts.default')

@section('title')Жанры@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center mt-5 mb-3">
        <h1 class="m">@yield('title')</h1>
        <h2 class="m">@yield('subtitle')</h2>
    </section>

    {!! Breadcrumbs::render('section', $section) !!}

    {!! ElementsHelper::getElements($request, $elements, $section->alt_name, $options) !!}

@stop