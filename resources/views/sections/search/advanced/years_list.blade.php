@extends('layouts.default')

@section('title'){{$title}}@stop

@section('subtitle'){{$subtitle}}@stop

@section('content')

    <section class="text-center">
        <h1 class="mt-5">@yield('title')</h1>
        <h2 class="mb-3">@yield('subtitle')</h2>
    </section>

    <?php
        $options = array(
        	'paginate' => false,
        );
    ?>

    <div class="row">

        <div class="col-md-12" style="column-count: 10;">

            {!! ElementsHelper::getList($request, $elements, $sub_section, $section, $options)!!}

        </div>

    </div>

@stop