@extends('layouts.default')

@section('title'){{$user->username}}@stop

@section('subtitle')Список желаемого@stop

@section('content')

    <section class="text-center">
        <h1 class="pt-5">@yield('title')</h1>
        <h2 class="pb-3">@yield('subtitle')</h2>
    </section>

    <section class="text-center mt-5">
        <h2 id="section">{!! $ru_section !!}</h2>
    </section>

    <div class="row">

        <div class="col-md-12">
            {!! ElementsHelper::getSection($request, $elements, $section) !!}
        </div>

    </div>

@stop