@extends('layouts.default')

@section('title')
	{!! $genre->name !!}
@stop

@section('subtitle')
	{!! $ru_section !!}
@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

  	<div class="book_additional_info">
    	<p>
    	</p>
    </div>

	<div class="item_card">
		<div class="item_img">@if(!empty($cover)) <img src="/data/img/genres/books/{!! $cover !!}.jpg" alt="{!! $genre->name !!}" /> @endif</div><!--
		--><div class="item_description">@if(!empty($genre->description)) <p>{!! nl2br($genre->description) !!}</p> @endif</div>
	</div>

	{!! Helpers::get_elements($elements, $section, $sort_options, true, true) !!}

@stop