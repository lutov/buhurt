@extends('layouts.default')

@section('title')
	Ошибка 404
@stop

@section('subtitle')
	Страница не найдена
@stop

@section('content')

  	<h1>@yield('title')</h1>
  	<h2>@yield('subtitle')</h2>

	<p><strong>Что же делать?</strong></p>

	<ul>
		<li>Проверьте набранный адрес</li>
		<li>Воспользуйтесь поиском</li>
		<li>Попробуйте найти искомое в через меню</li>
	</ul>

	<p>Если ничего не помогло, <a href="mailto:request@free-buhurt.club">напишите</a> нам. Что-нибудь придумаем.</p>

@stop