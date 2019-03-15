@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center mt-5 mb-3">
		{!! Breadcrumbs::render('element', $element) !!}
		<h1 class="">@yield('title')</h1>
		<h2 class="">@yield('subtitle')</h2>
	</section>

	<div itemscope itemtype="http://schema.org/MusicGroup" class="mt-5">

		<?php
		$info = array(
			'cover' => $cover,
		);
		?>

		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $info) !!}

	</div>

	@if(count($albums))

		<section class="text-center mt-5">
			<h2 id="books_published">Альбомы</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $albums, 'albums') !!}
			</div>

		</div>

	@endif

@stop