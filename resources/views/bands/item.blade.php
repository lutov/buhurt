@extends('layouts.default')

@section('title'){!! $band->name !!}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="mt-5">@yield('title')</h1>
		<h2 class="mb-3">@yield('subtitle')</h2>
	</section>

	<div class="row mt-5 align-top">

		<div class="col-md-3">

			@if(0 !== $photo) <img src="/data/img/covers/bands/{!! $photo !!}.jpg" alt="{!! $band->name !!}" class="" /> @endif

		</div>

		<div class="col-md-9">

			@if(!empty($band->bio)) <p>{!! nl2br($band->bio) !!}</p> @endif

		</div>

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