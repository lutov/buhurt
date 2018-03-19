@extends('layouts.default')

@section('title')
	{!! $company->name !!}
@stop

@section('subtitle')

@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

	<div class="element_card">
		<div class="element_img">@if(0 !== $company_logo) <img src="/data/img/covers/persons/{!! $company_logo !!}.jpg" alt="{!! $company->name !!}" /> @endif</div><!--
		--><div class="element_description">@if(!empty($company->description)) <p>{!! nl2br($company->description) !!}</p> @endif</div>
    </div>

	@if(Helpers::is_admin())

		{!! Form::open(array('action' => array('CompaniesController@transfer', $company->id), 'class' => 'transfer', 'method' => 'POST', 'files' => false)) !!}
		<p>{!! Form::text('recipient_id', $value = '', $attributes = array('placeholder' => 'Преемник', 'id' => 'recipient', 'class' => 'half')) !!}</p>
		<p>
			{!! Form::submit('Перенести', $attributes = array('id' => 'do_transfer')) !!}
		</p>
		{!! Form::close() !!}

	@endif

	@if(count($books_published))
	<h3>Изданные книги</h3>
	{!! Helpers::get_elements($books_published, 'books') !!}
	@endif

	@if(count($games_developed))
	<h3>Разработанные игры</h3>
	{!! Helpers::get_elements($games_developed, 'games') !!}
	@endif

    @if(count($games_published))
    <h3>Изданные игры</h3>
    {!! Helpers::get_elements($games_published, 'games') !!}
    @endif

@stop