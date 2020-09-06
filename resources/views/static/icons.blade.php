@extends('layouts.default')

@section('title')Иконки@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center mt-5 mb-5">

		<h1 class="">@yield('title')</h1>
		<p>Иконки достижений предоставлены <a href="http://thenounproject.com/">http://thenounproject.com/</a></p>

	</section>

	{!! Breadcrumbs::render('icons') !!}

	<div class="row">

	<?php
		$result = '';
		foreach($icons as $icon) {

			$result .= '<div class="col-md-3 mb-4">';
			$result .= '<img src="/data/img/achievements/raw/'.$icon.'.png" alt="" class="img-fluid">';
			$result .= '</div>';

		}
		echo $result;
	?>
	</div>

@stop