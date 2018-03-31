@extends('layouts.default')

@section('title'){{$year}}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">{{trim($year)}}-й год</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
	</section>

	<section class="text-center mt-5">
		<h2 id="section">{!! $ru_section !!}</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">
			{!! ElementsHelper::getElements($request, $elements, $section) !!}
		</div>

	</div>

@stop