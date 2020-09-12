@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center mt-5 mb-3">
        <h1 class="">@yield('title')</h1>
        <h2 class="">@yield('subtitle')</h2>
    </section>

    <div itemscope itemtype="http://schema.org/Person">

        {!! Breadcrumbs::render('element', $element) !!}

        {!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $options) !!}

    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach($titles as $key => $title)
            <li class="nav-item">
                <a class="nav-link @if(array_key_first($titles) === $key) active @endif" id="{{$key}}-tab"
                   data-toggle="tab" href="#{{$key}}" role="tab" aria-controls="{{$key}}"
                   aria-selected="@if(array_key_first($titles) === $key) true @else false @endif">
                    {{$title['name']}}
                    <span class="small text-secondary">({{$title['count']}})</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="myTabContent">

        @if(count($books_published))
            <div class="tab-pane fade @if(array_key_first($titles) === 'books') show active @endif" id="books"
                 role="tabpanel" aria-labelledby="books-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $books_published, 'books', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($games_developed))
            <div class="tab-pane fade @if(array_key_first($titles) === 'developer') show active @endif" id="developer"
                 role="tabpanel" aria-labelledby="developer-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $games_developed, 'games', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($games_published))
            <div class="tab-pane fade @if(array_key_first($titles) === 'publisher') show active @endif" id="publisher"
                 role="tabpanel" aria-labelledby="publisher-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $games_published, 'games', $options) !!}
                    </div>
                </div>
            </div>
        @endif

    </div>

@stop