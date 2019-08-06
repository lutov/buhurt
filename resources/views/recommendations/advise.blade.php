@extends('layouts.default')

@section('title')
	Совет
@stop

@section('subtitle')

@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
	</section>

	@if(!empty($book))

		<div itemscope itemtype="http://schema.org/Book">

			{!! ElementsHelper::getCardHeader($request, 'books', $book, $book->options) !!}

			{!! ElementsHelper::getCardBody($request, 'books', $book, $book->options) !!}

			{!! ElementsHelper::getCardFooter($request, 'books', $book, $book->options) !!}

		</div>

		{!! ElementsHelper::getCardScripts('books', $book->id) !!}

	@else



	@endif

@stop