@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle')@stop

@section('keywords'){!! $element->name !!}@if(count($keywords)), {{implode(', ', $keywords)}}@endif @stop
@section('description'){!! $element->name !!}@if(count($keywords)) — {{implode(', ', $keywords)}}@endif @stop

@section('content')

	<section class="text-center mt-5 mb-3">
		<h1 class="">@yield('title')</h1>
		<h2 class="">@yield('subtitle')</h2>
	</section>

	<div itemscope itemtype="http://schema.org/Person">

		{!! Breadcrumbs::render('element', $element) !!}

		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $options) !!}

	</div>

	<ul class="nav nav-tabs" id="myTab" role="tablist">
		@foreach($titles as $key => $title)
			<li class="nav-item">
				<a class="nav-link @if(array_key_first($titles) === $key) active @endif" id="{{$key}}-tab" data-toggle="tab" href="#{{$key}}" role="tab" aria-controls="{{$key}}" aria-selected="@if(array_key_first($titles) === $key) true @else false @endif">
					{{$title['name']}}
					<span class="small text-secondary">({{$title['count']}})</span>
				</a>
			</li>
		@endforeach
	</ul>
	<div class="tab-content" id="myTabContent">

		@if(count($books))
			<div class="tab-pane fade @if(array_key_first($titles) === 'writer') show active @endif" id="writer" role="tabpanel" aria-labelledby="writer-tab">
				<div class="row mt-5">
					<div class="col-md-12">
						{!! ElementsHelper::getElements($request, $books, 'books', $options) !!}
					</div>
				</div>
			</div>
		@endif

		@if(count($screenplays))
			<div class="tab-pane fade @if(array_key_first($titles) === 'screenwriter') show active @endif" id="screenwriter" role="tabpanel" aria-labelledby="screenwriter-tab">
				<div class="row mt-5">
					<div class="col-md-12">
						{!! ElementsHelper::getElements($request, $screenplays, 'films', $options) !!}
					</div>
				</div>
			</div>
		@endif

		@if(count($directions))
			<div class="tab-pane fade @if(array_key_first($titles) === 'director') show active @endif" id="director" role="tabpanel" aria-labelledby="director-tab">
				<div class="row mt-5">
					<div class="col-md-12">
						{!! ElementsHelper::getElements($request, $directions, 'films', $options) !!}
					</div>
				</div>
			</div>
		@endif

		@if(count($productions))
			<div class="tab-pane fade @if(array_key_first($titles) === 'producer') show active @endif" id="producer" role="tabpanel" aria-labelledby="producer-tab">
				<div class="row mt-5">
					<div class="col-md-12">
						{!! ElementsHelper::getElements($request, $productions, 'films', $options) !!}
					</div>
				</div>
			</div>
		@endif

		@if(count($roles))
			<div class="tab-pane fade @if(array_key_first($titles) === 'actor') show active @endif" id="actor" role="tabpanel" aria-labelledby="actor-tab">
				<div class="row mt-5">
					<div class="col-md-12">
						{!! ElementsHelper::getElements($request, $roles, 'films', $options) !!}
					</div>
				</div>
			</div>
		@endif

	</div>

@stop