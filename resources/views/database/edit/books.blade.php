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

                {!! Form::open(array('action' => 'Admin\DatabaseController@save', 'class' => 'add_book', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'edit') !!}
                {!! Form::hidden('section', $value = 'books') !!}
                {!! Form::hidden('element_id', $value = $element->id) !!}
                <p>{!! Form::text('name', $value = $element->name, $attributes = array('placeholder' => 'Название книги', 'id' => 'name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('alt_name', $value = implode('; ', $element->alt_name), $attributes = array('placeholder' => 'Альтернативное или оригинальное название книги', 'id' => 'alt_name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('writers', $value = DatatypeHelper::objectToJsArray($element->writers, '; ', true), $attributes = array('placeholder' => 'Автор', 'class' => 'form-control w-100', 'id' => 'writers')) !!}</p>
                <p>{!! Form::text('books_publishers', $value = DatatypeHelper::objectToJsArray($element->books_publishers, '; ', true), $attributes = array('placeholder' => 'Издатель', 'class' => 'form-control w-100', 'id' => 'books_publishers')) !!}</p>
                <p>{!! Form::textarea('description', $value = $element->description, $attributes = array('placeholder' => 'Аннотация', 'class' => 'form-control w-100', 'id' => 'annotation')) !!}</p>
                <p>{!! Form::text('genres', $value = DatatypeHelper::objectToJsArray($element->genres, '; ', true), $attributes = array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'genres')) !!}</p>
                <p>{!! Form::text('year', $value = $element->year, $attributes = array('placeholder' => 'Год написания', 'class' => 'form-control w-25')) !!}</p>
                <p>{!! Form::text('collections', $value = DatatypeHelper::objectToJsArray($element->collections, '; ', true), $attributes = array('placeholder' => 'Коллекции', 'class' => 'form-control w-100', 'id' => 'collections')) !!}</p>
                <p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
                {!! Form::submit('Сохранить', $attributes = array('id' => 'save', 'class' => 'btn btn-secondary', 'role' => 'button')) !!}
                {!! Form::close() !!}

            </div>

            <div class="col-md-3">

                <div class="card bg-dark text-white">
                    <img class="card-img-top" src="{!! ElementsHelper::getCover($section, $element->id) !!}" alt="">
                    <div class="card-body text-center">
                        <p class="card-text">Дополнительная информация</p>
                        <div class="btn-group">
                            {!! DummyHelper::getExtLink('fantlab', $element->name); !!}
                            {!! DummyHelper::getExtLink('wiki', $element->name); !!}
                        </div>
                        <div class="btn-group mt-3">
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex_images', $element->name); !!}
                        </div>
                    </div>
                </div>

                <div class="card bg-dark text-white mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#books_genres_container" aria-expanded="false" aria-controls="books_genres_container">
                        Жанры книг
                    </div>
                    <div class="collapse" id="books_genres_container">
                        {!! DatatypeHelper::objectToList(ElementsHelper::getGenres($section), 'genres_list') !!}
                    </div>
                </div>

                <div class="card bg-dark text-white mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#collections_list_container" aria-expanded="false" aria-controls="collections_list_container">
                        Коллекции
                    </div>
                    <div class="collapse" id="collections_list_container">
                        {!! DatatypeHelper::objectToList(ElementsHelper::getCollections(), 'collections_list') !!}
                    </div>
                </div>

                <div class="card bg-dark text-white mt-3">

                    <div id="transfer" class="card-body text-center">

                        {!! Form::open(array(
                            'action' => array(
                                'Data\BooksController@transfer', $element->id),
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

        <script type="text/javascript" src="/data/js/admin/books.js"></script>

    @else
        {!! DummyHelper::regToAdd() !!}
    @endif

@stop