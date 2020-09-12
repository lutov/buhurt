@php
    /** @var $element */
    /** @var bool $isAdmin */

    $description = !empty(trim($element->description));

    $tracks = $element->tracks;

    $genres = $element->genres;
    $year = $element->year;
    $book_publishers = $element->books_publishers && $element->books_publishers->count();
    $countries = $element->countries;
    $length = $element->length;
    $developers = $element->developers && $element->developers->count();
    $game_publishers = $element->games_publishers && $element->games_publishers->count();
    $platforms = $element->platforms && $element->platforms->count();
    $params = $genres || $year || $book_publishers || $countries || $length || $developers || $game_publishers || $platforms;

    $directors = $element->directors && $element->directors->count();
    $screenwriters = $element->screenwriters && $element->screenwriters->count();
    $producers = $element->producers && $element->producers->count();
    $actors = $element->actors && $element->actors->count();
    $crew = $directors || $screenwriters || $producers || $actors;

    $nav = $description || $tracks || $params || $crew;

    $collections = $element->collections && $element->collections->count();
    $relations = ($element->relations && $element->relations->count()) || ($isAdmin && method_exists($element, 'relations'));

    $footer = $collections || $relations;

    $card = $nav || $footer;
@endphp
@if($card)
<div class="col-lg-9 col-md-8 col-12" id="elementDetails">
    <div class="card @include('card.class') mb-4" id="cardDetails">
        @if($nav)
            <div class="card-header">
                <nav>
                    <div class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                        @if($description)
                            <a class="nav-item nav-link active" id="nav-description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">
                                Описание
                            </a>
                        @endif
                        @if($tracks)
                            <a class="nav-item nav-link active" id="nav-tracks-tab" data-toggle="tab" href="#tracks" role="tab" aria-controls="tracks" aria-selected="true">
                                Треки
                            </a>
                        @endif
                        @if($params)
                            <a class="nav-item nav-link" id="nav-params-tab" data-toggle="tab" href="#params" role="tab" aria-controls="params" aria-selected="false">
                                Параметры
                            </a>
                        @endif
                        @if($crew)
                            <a class="nav-item nav-link" id="nav-crew-tab" data-toggle="tab" href="#crew" role="tab" aria-controls="crew" aria-selected="false">
                                Съёмочная команда
                            </a>
                        @endif
                    </div>
                </nav>
            </div>
            <div class="tab-content" id="nav-tabContent">
                @if($description)
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="nav-description-tab">
                        <div class="card-body" itemprop="description">
                            <p class="card-text">
                                {!! nl2br($element->description); !!}
                            </p>
                        </div>
                    </div>
                @endif
                @if($tracks)
                    <div class="tab-pane fade show active" id="tracks" role="tabpanel" aria-labelledby="nav-tracks-tab">
                        <ol class="list-group list-group-flush">
                            <li class="list-group-item">
                                {!! DatatypeHelper::objectToJsArray($element->tracks, '</li><li class="list-group-item">', true) !!}
                            </li>
                        </ol>
                        <div class="card-body">
                            <div class="btn-group">
                                {!! DummyHelper::getExtLink('yandex_music', $element->name); !!}
                                {!! DummyHelper::getExtLink('google_play', $element->name); !!}
                            </div>
                        </div>
                    </div>
                @endif
                @if($params)
                    <div class="tab-pane fade show" id="params" role="tabpanel" aria-labelledby="nav-params-tab">
                        <ul class="list-group list-group-flush small">
                            @if($genres)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Жанр
                                    <span class="">
                                    {!! DatatypeHelper::arrayToString($element->genres, ', ', '/genres/', false, 'genre'); !!}
                                </span>
                                </li>
                            @endif
                            @if($year)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Год
                                    <span class="">
                                    <a itemprop="datePublished" href="/years/{!! $element->year !!}">{!! $element->year !!}</a>
                                </span>
                                </li>
                            @endif
                            @if($book_publishers)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Издатель
                                    <span class="">
                                    {!! DatatypeHelper::arrayToString($element->books_publishers, ', ', '/companies/', false, 'publisher'); !!}
                                </span>
                                </li>
                            @endif
                            @if($countries)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Страна
                                    <span class="">
                                    {!! DatatypeHelper::arrayToString($element->countries, ', ', '/countries/'); !!}
                                </span>
                                </li>
                            @endif
                            @if($length)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="">
                                    Продолжительность&nbsp;<abbr title="Продолжительность фильма или отдельного эпизода сериала" class="small text-muted">?</abbr>
                                </span>
                                    <span class="">
                                    <meta itemprop="duration" content="T{!! $element->length !!}M" />{!! $element->length !!} мин.
                                </span>
                                </li>
                            @endif
                            @if($developers)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Разработчик
                                    <span class="">
                                    {!! DatatypeHelper::arrayToString($element->developers, ', ', '/companies/', false, 'creator'); !!}
                                </span>
                                </li>
                            @endif
                            @if($game_publishers)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Издатель
                                    <span class="">
                                    {!! DatatypeHelper::arrayToString($element->games_publishers, ', ', '/companies/', false, 'publisher'); !!}
                                </span>
                                </li>
                            @endif
                            @if($platforms)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Платформы
                                    <span class="">
                                    {!! DatatypeHelper::arrayToString($element->platforms, ', ', '/platforms/'); !!}
                                </span>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
                @if($crew)
                    <div class="tab-pane fade" id="crew" role="tabpanel" aria-labelledby="nav-crew-tab">
                        <ul class="list-group list-group-flush small">
                            @if($directors)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Режиссёр
                                    <span class="">
                                    {!! DatatypeHelper::arrayToString($element->directors, ', ', '/persons/', false, 'director'); !!}
                                </span>
                                </li>
                            @endif
                            @if($screenwriters)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Сценарий
                                    <span class="">
                                    {!! DatatypeHelper::arrayToString($element->screenwriters, ', ', '/persons/', false, 'creator'); !!}
                                </span>
                                </li>
                            @endif
                            @if($producers)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Продюсер
                                    <span class="">
                                    {!! DatatypeHelper::arrayToString($element->producers, ', ', '/persons/', false, 'producer'); !!}
                                </span>
                                </li>
                            @endif
                            @if($actors)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    В ролях
                                    <span class="">
                                    {!! DatatypeHelper::arrayToString($element->actors, ', ', '/persons/', false, 'actor'); !!}
                                </span>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
        @endif
        @if($footer)
            <div class="card-footer">
                 @if($collections)
                    <span class="small card-link">
                        Коллекции: {!! DatatypeHelper::arrayToString($element->collections, ', ', '/collections/', false, "isPartOf") !!}
                    </span>
                @endif
                @if($relations)
                     <span class="small card-link">
                        <a href="/{{$section->alt_name}}/{{$element->id}}/relations/">
                            Связанные произведения
                            @if($element->relations)
                                ({{$element->relations->count()}})
                            @endif
                        </a>
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>
@endif
