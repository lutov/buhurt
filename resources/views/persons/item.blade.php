@extends('layouts.default')

@section('title')
	{!! $person->name !!}
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
		<div class="element_img">@if(0 !== $photo) <img src="/data/img/covers/persons/{!! $photo !!}.jpg" alt="{!! $person->name !!}" /> @endif</div><!--
		--><div class="element_description">
			@if(!empty($person->bio)) <p>{!! nl2br($person->bio) !!}</p> @endif
			
			@if(count($books))
				<p>Жанры: {!! Helpers::array2string($top_genres, ', ', '/genres/books/') !!}</p>
			@endif
		</div>
    </div>

	@if(Helpers::is_admin())

		{!! Form::open(array('action' => array('PersonsController@transfer', $person->id), 'class' => 'transfer', 'method' => 'POST', 'files' => false)) !!}
		<p>{!! Form::text('recipient_id', $value = '', $attributes = array('placeholder' => 'Преемник', 'id' => 'recipient', 'class' => 'half')) !!}</p>
		<p>
			{!! Form::submit('Перенести', $attributes = array('id' => 'do_transfer')) !!}
		</p>
		{!! Form::close() !!}

	@endif

	@if(count($books))
	<h3>Писатель</h3>
	{!! Helpers::get_elements($books, 'books') !!}
	@endif

	@if(count($screenplays))
	<h3>Сценарист</h3>
	{!! Helpers::get_elements($screenplays, 'films') !!}
	@endif

    @if(count($directions))
    <h3>Режиссер</h3>
    {!! Helpers::get_elements($directions, 'films') !!}
    @endif

	@if(count($productions))
	<h3>Продюсер</h3>
	{!! Helpers::get_elements($productions, 'films') !!}
	@endif

    @if(count($actions))
    <h3>Актёр</h3>
    {!! Helpers::get_elements($actions, 'films') !!}
    @endif

@stop