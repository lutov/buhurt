@extends('layouts.default')

@section('title'){!! $company->name !!}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
		<ul class="list-inline">

			@if(count($books_published))<li class="list-inline-item"><a href="#books_published">Изданные книги</a></li>@endif
			@if(count($games_developed))<li class="list-inline-item"><a href="#games_developed">Разработанные игры</a></li>@endif
			@if(count($games_published))<li class="list-inline-item"><a href="#games_published">Изданные игры</a></li>@endif

		</ul>
	</section>

	<div class="row mt-5 align-top">

		<div class="col-md-3">

			@if(0 !== $company_logo) <img src="/data/img/covers/persons/{!! $company_logo !!}.jpg" alt="{!! $company->name !!}" /> @endif

		</div>

		<div class="col-md-9">

			@if(!empty($company->description)) <p>{!! nl2br($company->description) !!}</p> @endif

		</div>

	</div>

	@if(count($books_published))

		<section class="text-center mt-5">
			<h2 id="books_published">Изданные книги</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $books_published, 'books') !!}
			</div>

		</div>

	@endif

	@if(count($games_developed))

		<section class="text-center mt-5">
			<h2 id="games_developed">Разработанные игры</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $games_developed, 'games') !!}
			</div>

		</div>

	@endif

	@if(count($games_published))

		<section class="text-center mt-5">
			<h2 id="games_published">Изданные игры</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $games_published, 'games') !!}
			</div>

		</div>

	@endif

	@if(RolesHelper::isAdmin($request))

		<div class="form-group">
			{!! Form::open(array('action' => array('CompaniesController@transfer', $company->id), 'class' => 'transfer', 'method' => 'POST', 'files' => false)) !!}
			<p>{!! Form::text('recipient_id', $value = '', $attributes = array(
			'placeholder' => 'Преемник',
			'id' => 'recipient',
			'class' => 'form-control'
		)) !!}</p>
			<p>
				{!! Form::submit('Перенести', $attributes = array(
                    'id' => 'do_transfer',
                    'type' => 'button',
                    'class' => 'btn btn-secondary'
                )) !!}
			</p>
			{!! Form::close() !!}
		</div>

	@endif

@stop