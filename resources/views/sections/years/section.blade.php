@extends('layouts.default')

@section('title'){{$section->name}}@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center mt-5 mb-3">
        <h1 class="m">@yield('title')</h1>
        <h2 class="m">@yield('subtitle')</h2>
    </section>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach($titles as $key => $title)
            <li class="nav-item">
                <a class="nav-link @if(array_key_first($titles) === $key) active @endif" id="{{$key}}-tab" data-toggle="tab" href="#{{$key}}" role="tab" aria-controls="{{$key}}" aria-selected="@if(array_key_first($titles) === $key) true @else false @endif">
                    {{$title['name']}}
                    <span class="small text-secondary">({{$title['count']}})</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="myTabContent">

        @if(count($books))
            @php
                $options['anchor'] = 'books';
            @endphp
            <div class="tab-pane fade @if(array_key_first($titles) === 'books') show active @endif" id="books" role="tabpanel" aria-labelledby="books-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getList($request, $books, 'years', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($films))
            @php
                $options['anchor'] = 'films';
            @endphp
            <div class="tab-pane fade @if(array_key_first($titles) === 'films') show active @endif" id="films" role="tabpanel" aria-labelledby="films-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getList($request, $films, 'years', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($games))
            @php
                $options['anchor'] = 'games';
            @endphp
            <div class="tab-pane fade @if(array_key_first($titles) === 'games') show active @endif" id="games" role="tabpanel" aria-labelledby="games-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getList($request, $games, 'years', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($albums))
            @php
                $options['anchor'] = 'albums';
            @endphp
            <div class="tab-pane fade @if(array_key_first($titles) === 'albums') show active @endif" id="albums" role="tabpanel" aria-labelledby="albums-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getList($request, $albums, 'years', $options) !!}
                    </div>
                </div>
            </div>
        @endif

    </div>

@stop