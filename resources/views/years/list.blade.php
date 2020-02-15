@extends('layouts.default')

@section('title')Календарь@stop

@section('subtitle'){{$section->name}}@stop

@section('content')

    <section class="text-center mt-5 mb-3">
        <h1 class="m">@yield('title')</h1>
        <h2 class="m">@yield('subtitle')</h2>
    </section>

    <div class="row mt-5">

        <div class="col-md-12">

            {!! Breadcrumbs::render('section', $section) !!}

            {!! ElementsHelper::getList($request, $elements, 'years', $options) !!}

        </div>

    </div>

@stop