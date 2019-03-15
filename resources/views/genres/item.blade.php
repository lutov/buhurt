@extends('layouts.default')

@section('title'){{$section->name}}@stop

@section('subtitle'){{$element->name}}@stop

@section('content')

	<section class="text-center mt-5 mb-3">
		<h1 class="">@yield('title')</h1>
		<h2 class="">@yield('subtitle')</h2>
	</section>

	<div itemscope itemtype="">

		{!! Breadcrumbs::render('element', $element) !!}

		<?php
		$info = array(
			'cover' => $cover,
		);
		?>

		{!! ElementsHelper::getCardBody($request, $parent->alt_name, $element, $info) !!}

	</div>

	<div class="row mt-5">

		<div class="col-md-12">

			{!! ElementsHelper::getElements($request, $elements, $section->alt_name) !!}

		</div>

	</div>

@stop