@extends('layouts.default')

@section('title'){{$parent->name}}@stop

@section('subtitle'){{$section->name}}@stop

@section('content')

    <section class="text-center mt-5 mb-3">
        <h1 class="m">@yield('title')</h1>
        <h2 class="m">@yield('subtitle')</h2>
    </section>

    <div class="row mt-5">

        <div class="col-md-12">

            {!! Breadcrumbs::render('section', $section) !!}

            <div style="column-count: 10; column-width: 5em;">
				<?php $options = array('paginate' => false,); ?>
                {!! ElementsHelper::getList($request, $elements, $parent->alt_name, $section->alt_name, $options)!!}
            </div>

        </div>

    </div>

@stop