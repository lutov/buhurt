@extends('layouts.default')

@section('title'){!! $genre->name !!}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
	</section>

	<div class="row mt-5 align-top">

		<div class="col-md-3">

			@if(!empty($cover)) <img src="/data/img/genres/{!! $section !!}/{!! $cover !!}.jpg" alt="{!! $genre->name !!}" class="img-fluid" /> @endif

		</div>

		<div class="col-md-9">

			@if(!empty($genre->description)) <p>{!! nl2br($genre->description) !!}</p> @endif

		</div>

	</div>

	<section class="text-center mt-5">
		<h2 id="section">{!! $ru_section !!}</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">
			{!! ElementsHelper::getElements($request, $elements, $section) !!}
		</div>

	</div>

@stop