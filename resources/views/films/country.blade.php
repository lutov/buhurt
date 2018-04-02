@extends('layouts.default')

@section('title'){{$country->name}}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="mt-5">@yield('title')</h1>
		<h2 class="mb-3">@yield('subtitle')</h2>
	</section>

	<section class="text-center mt-5">
		<h2 id="films">{{$ru_section}}</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">

			{!! ElementsHelper::getElements($request, $films, $section)!!}

		</div>

	</div>

@stop