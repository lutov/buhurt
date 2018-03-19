@extends('layouts.default')

@section('title')
	Авторы иконок
@stop

@section('subtitle')

@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

	<p>Все иконки взяты с сайта <a href="http://thenounproject.com/">http://thenounproject.com/</a>. Авторы указаны на картинках.</p>

	<div class="raw_achievements">
	<?php
		$result = '';
		foreach($icons as $icon)
		{
			$result .= '<img src="/data/img/achievements/raw/'.$icon.'.png" alt="">';
		}
		echo $result;
	?>
	</div>

@stop