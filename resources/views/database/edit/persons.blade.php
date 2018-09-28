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

                {!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_person', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'edit') !!}
                {!! Form::hidden('section', $value = 'persons') !!}
                {!! Form::hidden('element_id', $value = $element->id) !!}
                <p>{!! Form::text('name', $value = $element->name, $attributes = array('placeholder' => 'Имя', 'id' => 'game_name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::textarea('description', $value = $element->description, $attributes = array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'game_description')) !!}</p>
                <p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
                {!! Form::submit('Сохранить', $attributes = array('id' => 'save', 'class' => 'btn btn-secondary', 'role' => 'button')) !!}
                {!! Form::close() !!}

            </div>

            <div class="col-md-3">

                <div class="card">
                    <img class="card-img-top" src="/data/img/covers/{!! $section !!}/{!! $element_cover !!}.jpg" alt="">
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

                    <div id="transfer" class="card-body text-center">

                        {!! Form::open(array(
                            'action' => array(
                                'PersonsController@transfer', $element->id),
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

        <!--script type="text/javascript" src="/data/js/admin/persons.js"></script-->

    @else
        {!! DummyHelper::regToAdd() !!}
    @endif

@stop