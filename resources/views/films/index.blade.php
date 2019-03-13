@extends('layouts.default')

@section('title'){{$section->name}}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center mt-5 mb-3">
		{!! Breadcrumbs::render('section', $section) !!}
		<h1>@yield('title')</h1>
	</section>

	{!! ElementsHelper::getElements($request, $elements, $section->alt_name, $options) !!}

@stop