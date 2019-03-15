@extends('layouts.default')

@section('title'){{$element->name}}@stop

@section('subtitle'){{$section->name}}@stop

@section('content')

	<section class="text-center mt-5 mb-3">
		<h1 class="">@yield('title')</h1>
		<h2 class="">@yield('subtitle')</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">

			{!! Breadcrumbs::render('element', $element) !!}

			{!! ElementsHelper::getElements($request, $elements, $elements->first()->section()->alt_name, $options)!!}

		</div>

	</div>

@stop