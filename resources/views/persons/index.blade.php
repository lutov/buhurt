@extends('layouts.default')

@section('title'){{$section->name}}@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center mt-5 mb-3">
        <h1>@yield('title')</h1>
    </section>

    {!! Breadcrumbs::render('section', $section) !!}

    {!! ElementsHelper::getElements($request, $elements, $section->alt_name, $options) !!}

@stop