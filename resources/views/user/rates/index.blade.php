@extends('layouts.default')

@section('title'){{$user->username}}@stop

@section('subtitle'){{$section_name}}@stop

@section('content')

    <section class="text-center">
        <h1 class="pt-5">@yield('title')</h1>
        <h2 class="pb-3">@yield('subtitle')</h2>

        @if(Auth::check() && (Auth::user()->id == $user->id))
            <p id="download_table_button">
                <a href="/user/{{$user->id}}/rates/{{$section}}/export">
                    Скачать таблицу
                </a>
            </p>
        @endif

    </section>

    <div class="row mt-5">

        <div class="col-md-12">
            {!! ElementsHelper::getElements($request, $elements, $section, $options) !!}
        </div>

    </div>

@stop