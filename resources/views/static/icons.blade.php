@extends('layouts.default')

@section('title')Авторы иконок@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
		<p>Все иконки взяты с сайта <a href="http://thenounproject.com/">http://thenounproject.com/</a>. Авторы указаны на картинках.</p>
	</section>

	<div class="row mt-5">
	<?php
		$result = '';
		foreach($icons as $icon) {

			$result .= '<div class="col-md-3">';
			$result .= '<img src="/data/img/achievements/raw/'.$icon.'.png" alt="" class="img-fluid">';
			$result .= '</div>';

		}
		echo $result;
	?>
	</div>

@stop