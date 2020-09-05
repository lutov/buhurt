@extends('layouts.default')
@section('title'){!! $element->name !!}@stop
@section('subtitle') Редактировать @stop
@section('content')
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
                <div class="card @include('widgets.card-class')">
                    {!! Form::open(array('action' => 'Admin\DatabaseController@save', 'class' => 'add_platform', 'method' => 'POST', 'files' => true)) !!}
                    <div class="card-header">
                        <h1 class="card-title">@yield('title')</h1>
                        <h2 class="card-subtitle mb-2 text-muted">@yield('subtitle')</h2>
                        {!! Form::hidden('action', 'edit') !!}
                        {!! Form::hidden('section', 'platforms') !!}
                        {!! Form::hidden('element_id', $element->id) !!}
                    </div>
                    <div class="card-body">
                        <p>{!! Form::text('name', $element->name, array('placeholder' => 'Название', 'id' => 'platform_name', 'class' => 'form-control w-100')) !!}</p>
                        <p>{!! Form::textarea('description', $element->description, array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'game_description')) !!}</p>
                        <b>Обложка</b> {!! Form::file('cover'); !!}
                    </div>
                    <div class="card-footer">
                        {!! Form::submit('Сохранить', array('id' => 'save', 'class' => 'btn btn-secondary', 'role' => 'button')) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="card @include('widgets.card-class')">
                    <img class="card-img-top" src="{!! ElementsHelper::getCover($section, $element->id) !!}" alt="">
                    <div class="card-body text-center">
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
            </div>
        </div>
        <!--script type="text/javascript" src="/data/js/admin/bands.js"></script-->
    @else
        {!! DummyHelper::regToAdd() !!}
    @endif
@stop
