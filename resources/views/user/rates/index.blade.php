@extends('layouts.default')

@section('title')
	{{$user->username}} | {{$section_name}}
@stop

@section('subtitle')
	Оценки
@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>
        @yield('title')
        @if(Auth::check() && (Auth::user()->id == $user->id))
            <p id="download_table_button">
                <a href="/user/{{$user->id}}/rates/{{$section}}/export">
                    <img src="/data/img/design/table.svg" alt="Скачать таблицу" title="Скачать таблицу">
                </a>
            </p>
        @endif
    </h1>

	{!! Helpers::get_elements($elements, $section, $sort_options, true, true) !!}

@stop