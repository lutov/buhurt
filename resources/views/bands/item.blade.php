@extends('layouts.default')

@section('title')
	{!! $band->name !!}
@stop

@section('subtitle')

@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

  	<div class="book_additional_info">
    	<p>
    	</p>
    </div>

	<div class="element_card">
		<div class="element_img">@if(0 !== $photo) <img src="/data/img/covers/bands/{!! $photo !!}.jpg" alt="{!! $band->name !!}" /> @endif</div><!--
		--><div class="element_description">@if(!empty($band->bio)) <p>{!! nl2br($band->bio) !!}</p> @endif</div>
    </div>

	@if(count($albums))
	<h3>Альбомы</h3>
	{!! Helpers::get_elements($albums, 'albums') !!}
	@endif

@stop