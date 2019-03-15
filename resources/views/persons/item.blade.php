@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center mt-5 mb-3">
		<h1 class="">@yield('title')</h1>
		<h2 class="">@yield('subtitle')</h2>
		<ul class="list-inline mt-3">

			@if(count($books))<li class="list-inline-item"><a href="#writer">Писатель</a></li>@endif
			@if(count($screenplays))<li class="list-inline-item"><a href="#screenwriter">Сценарист</a></li>@endif
			@if(count($directions))<li class="list-inline-item"><a href="#director">Режиссёр</a></li>@endif
			@if(count($productions))<li class="list-inline-item"><a href="#producer">Продюссер</a></li>@endif
			@if(count($actions))<li class="list-inline-item"><a href="#actor">Актёр</a></li>@endif
			<?//@if(RolesHelper::isAdmin($request))<li class="list-inline-item"><a href="#transfer">Преемник</a></li>@endif?>

		</ul>
	</section>

	<div itemscope itemtype="http://schema.org/Person">

		{!! Breadcrumbs::render('element', $element) !!}

		<?php
		$info = array(
			'top_genres' => $top_genres,
			'cover' => $cover,
		);
		?>

		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $info) !!}

	</div>

	@if(count($books))

		<section class="text-center mt-5">
			<h2 id="writer">Писатель</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $books, 'books', $options) !!}
			</div>

		</div>

	@endif

	@if(count($screenplays))

		<section class="text-center mt-5">
			<h2 id="screenwriter">Сценарист</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $screenplays, 'films', $options) !!}
			</div>

		</div>

	@endif

    @if(count($directions))

		<section class="text-center mt-5">
			<h2 id="director">Режиссер</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $directions, 'films', $options) !!}
			</div>

		</div>

    @endif

	@if(count($productions))

		<section class="text-center mt-5">
			<h2 id="producer">Продюсер</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $productions, 'films', $options) !!}
			</div>

		</div>

	@endif

    @if(count($actions))

		<section class="text-center mt-5">
			<h2 id="actor">Актёр</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $actions, 'films', $options) !!}
			</div>

		</div>

    @endif

@stop