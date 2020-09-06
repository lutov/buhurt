@extends('layouts.default')

@section('title'){{$section_name}}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="mt-5">@yield('title')</h1>
		<h2 class="mb-3">@yield('subtitle')</h2>
	</section>

	<div class="row">

		<div class="col-md-12">

			{!! ElementsHelper::getEvents($request, $elements, $section, '')!!}

		</div>

	</div>

@stop