@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle')Редактировать элемент@stop

@section('content')

    <section class="text-center">
        <h1 class="mt-5">@yield('title')</h1>
        <h2 class="mb-3">@yield('subtitle')</h2>
    </section>

    @if(Auth::check())

        @if(count($errors))

            <div class="row mt-5">

                <div class="col-md-12">

                    @foreach ($errors->all() as $error)
                        <p>{!! $error !!}</p>
                    @endforeach

                </div>

            </div>

        @endif

        <div class="row mt-5">

            <div class="col-md-9">

                {!! Form::open(array('action' => 'Admin\DatabaseController@save', 'class' => 'add_game', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'edit') !!}
                {!! Form::hidden('section', $value = 'games') !!}
                {!! Form::hidden('element_id', $value = $element->id) !!}
                <p>{!! Form::text('name', $value = $element->name, $attributes = array('placeholder' => 'Название игры', 'id' => 'name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('alt_name', $value = implode('; ', $element->alt_name), $attributes = array('placeholder' => 'Альтернативное или оригинальное название игры', 'id' => 'alt_name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('developers', $value = DatatypeHelper::objectToJsArray($element->developers, '; ', true), $attributes = array('placeholder' => 'Разработчик', 'class' => 'form-control w-100', 'id' => 'developers')) !!}</p>
                <p>{!! Form::text('games_publishers', $value = DatatypeHelper::objectToJsArray($element->games_publishers, '; ', true), $attributes = array('placeholder' => 'Издатель', 'class' => 'form-control w-100', 'id' => 'games_publishers')) !!}</p>
                <p>{!! Form::textarea('description', $value = $element->description, $attributes = array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'description')) !!}</p>
                <p>{!! Form::text('genres', $value = DatatypeHelper::objectToJsArray($element->genres, '; ', true), $attributes = array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'genres')) !!}</p>
                <p>{!! Form::text('platforms', $value = DatatypeHelper::objectToJsArray($element->platforms, '; ', true), $attributes = array('placeholder' => 'Платформа', 'class' => 'form-control w-100', 'id' => 'platforms')) !!}</p>
                <p>{!! Form::text('year', $value = $element->year, $attributes = array('placeholder' => 'Год выпуска', 'class' => 'form-control w-25')) !!}</p>
                <p>{!! Form::text('collections', $value = DatatypeHelper::objectToJsArray($element->collections, '; ', true), $attributes = array('placeholder' => 'Коллекции', 'class' => 'form-control w-100', 'id' => 'collections')) !!}</p>
                <p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
                {!! Form::submit('Сохранить', $attributes = array('id' => 'save', 'class' => 'btn btn-secondary', 'role' => 'button')) !!}
                {!! Form::close() !!}

            </div>

            <div class="col-md-3">

                <div class="card">
                    <img class="card-img-top" src="{!! ElementsHelper::getCover($section, $element->id) !!}" alt="">
                    <div class="card-body text-center">
                        <p class="card-text">Дополнительная информация</p>
                        <div class="btn-group">
                            {!! DummyHelper::getExtLink('wiki', $element->name); !!}
                            {!! DummyHelper::getExtLink('wiki_en', $element->name); !!}
                        </div>
                        <div class="btn-group mt-3">
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex_images', $element->name); !!}
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#games_genres_container" aria-expanded="false" aria-controls="games_genres_container">
                        Жанры игр
                    </div>
                    <div class="collapse" id="games_genres_container">
                        {!! DatatypeHelper::objectToList(ElementsHelper::getGenres($section), 'genres_list') !!}
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#platforms_container" aria-expanded="false" aria-controls="platforms_container">
                        Платформы
                    </div>
                    <div class="collapse" id="platforms_container">
                        {!! DatatypeHelper::objectToList(ElementsHelper::getPlatforms(), 'platforms_list') !!}
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#collections_list_container" aria-expanded="false" aria-controls="collections_list_container">
                        Коллекции
                    </div>
                    <div class="collapse" id="collections_list_container">
                        {!! DatatypeHelper::objectToList(ElementsHelper::getCollections(), 'collections_list') !!}
                    </div>
                </div>

                <div class="card mt-3">

                    <div id="transfer" class="card-body text-center">

                        {!! Form::open(array(
                            'action' => array(
                                'Data\GamesController@transfer', $element->id),
                                'class' => 'transfer',
                                'method' => 'POST',
                                'files' => false
                            )
                        ) !!}

                        <div>
                            {!! Form::text('recipient_id', $value = '', $attributes = array(
                                'placeholder' => 'Преемник',
                                'id' => 'recipient',
                                'class' => 'form-control'
                            )) !!}
                        </div>

                        <div class="btn-group mt-3">
                            {!! Form::submit('Перенести', $attributes = array(
                                'id' => 'do_transfer',
                                'type' => 'button',
                                'class' => 'btn btn-sm btn-outline-primary'
                            )) !!}
                        </div>

                        {!! Form::close() !!}

                    </div>

                </div>

            </div>

        </div>

        <script type="text/javascript" src="/data/js/admin/games.js"></script>

    @else
        {!! DummyHelper::regToAdd() !!}
    @endif

@stop