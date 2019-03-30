@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center mt-5 mb-3">
		<h1 class="">@yield('title')</h1>
		<h2 class="">@yield('subtitle')</h2>
		<ul class="list-inline mt-3">

			@if(count($books_published))<li class="list-inline-item"><a href="#books_published">Изданные книги</a></li>@endif
			@if(count($games_developed))<li class="list-inline-item"><a href="#games_developed">Разработанные игры</a></li>@endif
			@if(count($games_published))<li class="list-inline-item"><a href="#games_published">Изданные игры</a></li>@endif
			<?//@if(RolesHelper::isAdmin($request))<li class="list-inline-item"><a href="#transfer">Преемник</a></li>@endif?>

		</ul>
	</section>

	{!! Breadcrumbs::render('element', $element) !!}

	<div itemscope itemtype="http://schema.org/Person">

		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $options) !!}

	</div>

	@if(count($books_published))

		<section class="text-center mt-5">
			<h2 id="books_published">Изданные книги</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $books_published, 'books', $options) !!}
			</div>

		</div>

	@endif

	@if(count($games_developed))

		<section class="text-center mt-5">
			<h2 id="games_developed">Разработанные игры</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $games_developed, 'games', $options) !!}
			</div>

		</div>

	@endif

	@if(count($games_published))

		<section class="text-center mt-5">
			<h2 id="games_published">Изданные игры</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $games_published, 'games', $options) !!}
			</div>

		</div>

	@endif

@stop