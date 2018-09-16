@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center mb-5">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
		<ul class="list-inline">

			@if(count($books))<li class="list-inline-item"><a href="#writer">Писатель</a></li>@endif
			@if(count($screenplays))<li class="list-inline-item"><a href="#screenwriter">Сценарист</a></li>@endif
			@if(count($directions))<li class="list-inline-item"><a href="#director">Режиссёр</a></li>@endif
			@if(count($productions))<li class="list-inline-item"><a href="#producer">Продюссер</a></li>@endif
			@if(count($actions))<li class="list-inline-item"><a href="#actor">Актёр</a></li>@endif
			<?//@if(RolesHelper::isAdmin($request))<li class="list-inline-item"><a href="#transfer">Преемник</a></li>@endif?>

		</ul>
	</section>

	<div itemscope itemtype="http://schema.org/Person">

		<?php
		$info = array(
			//'rate' => $rate,
			//'wanted' => $wanted,
			//'not_wanted' => $not_wanted,
			//'genres' => $genres,
			'top_genres' => $top_genres,
			'cover' => $cover,
			//'similar' => $similar,
			//'collections' => $collections,
			//'relations' => $relations,
			//'writers' => $writers,
			//'publishers' => $publishers,
		);
		?>

		{!! ElementsHelper::getCardBody($request, $section, $element, $info) !!}

	</div>

	@if(count($books))

		<section class="text-center mt-5">
			<h2 id="writer">Писатель</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $books, 'books') !!}
			</div>

		</div>

	@endif

	@if(count($screenplays))

		<section class="text-center mt-5">
			<h2 id="screenwriter">Сценарист</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $screenplays, 'films') !!}
			</div>

		</div>

	@endif

    @if(count($directions))

		<section class="text-center mt-5">
			<h2 id="director">Режиссер</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $directions, 'films') !!}
			</div>

		</div>

    @endif

	@if(count($productions))

		<section class="text-center mt-5">
			<h2 id="producer">Продюсер</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $productions, 'films') !!}
			</div>

		</div>

	@endif

    @if(count($actions))

		<section class="text-center mt-5">
			<h2 id="actor">Актёр</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $actions, 'films') !!}
			</div>

		</div>

    @endif

@stop