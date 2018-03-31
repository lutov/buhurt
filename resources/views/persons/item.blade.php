@extends('layouts.default')

@section('title'){!! $person->name !!}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
		<ul class="list-inline">

			@if(count($books))<li class="list-inline-item"><a href="#writer">Писатель</a></li>@endif
			@if(count($screenplays))<li class="list-inline-item"><a href="#screenwriter">Сценарист</a></li>@endif
			@if(count($directions))<li class="list-inline-item"><a href="#director">Режиссёр</a></li>@endif
			@if(count($productions))<li class="list-inline-item"><a href="#producer">Продюссер</a></li>@endif
			@if(count($actions))<li class="list-inline-item"><a href="#actor">Актёр</a></li>@endif

		</ul>
	</section>

	<div class="row mt-5 align-top">

		<div class="col-md-3">

			@if(0 !== $photo) <img src="/data/img/covers/persons/{!! $photo !!}.jpg" alt="{!! $person->name !!}" class="img-fluid" /> @endif

		</div>

		<div class="col-md-9">

			@if(!empty($person->bio)) <p>{!! nl2br($person->bio) !!}</p> @endif

			@if(count($books))<p>Жанры: {!! DatatypeHelper::arrayToString($top_genres, ', ', '/genres/books/') !!}</p>@endif

		</div>

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

	@if(RolesHelper::isAdmin($request))

		<div class="form-group">
		{!! Form::open(array('action' => array('PersonsController@transfer', $person->id), 'class' => 'transfer', 'method' => 'POST', 'files' => false)) !!}
		<p>{!! Form::text('recipient_id', $value = '', $attributes = array(
			'placeholder' => 'Преемник',
			'id' => 'recipient',
			'class' => 'form-control'
		)) !!}</p>
		<p>
			{!! Form::submit('Перенести', $attributes = array(
				'id' => 'do_transfer',
				'type' => 'button',
				'class' => 'btn btn-secondary'
			)) !!}
		</p>
		{!! Form::close() !!}
		</div>

	@endif

@stop