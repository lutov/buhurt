@extends('layouts.default')

@section('title'){{$platform->name}}@stop

@section('subtitle'){{$ru_section}}@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">

			{!! ElementsHelper::getElements($request, $games, $section) !!}

		</div>

	</div>

@stop