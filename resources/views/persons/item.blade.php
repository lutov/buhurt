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

	<div class="card @include('widgets.card-class')">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
				@foreach($titles as $key => $title)
					<li class="nav-item">
						<a class="nav-link @if(array_key_first($titles) === $key) active @endif" id="{{$key}}-tab" data-toggle="tab" href="#{{$key}}" role="tab" aria-controls="{{$key}}" aria-selected="@if(array_key_first($titles) === $key) true @else false @endif">
							{{$title['name']}}
							<span class="small text-secondary">({{$title['count']}})</span>
						</a>
					</li>
				@endforeach
			</ul>
		</div>
	</div>

	<div class="tab-content" id="myTabContent">
		@if(count($books))
			<div class="tab-pane fade @if(array_key_first($titles) === 'writer') show active @endif" id="writer" role="tabpanel" aria-labelledby="writer-tab">
				{!! ElementsHelper::getElements($request, $books, 'books', $options) !!}
			</div>
		@endif
		@if(count($screenplays))
			<div class="tab-pane fade @if(array_key_first($titles) === 'screenwriter') show active @endif" id="screenwriter" role="tabpanel" aria-labelledby="screenwriter-tab">
				{!! ElementsHelper::getElements($request, $screenplays, 'films', $options) !!}
			</div>
		@endif
		@if(count($directions))
			<div class="tab-pane fade @if(array_key_first($titles) === 'director') show active @endif" id="director" role="tabpanel" aria-labelledby="director-tab">
				{!! ElementsHelper::getElements($request, $directions, 'films', $options) !!}
			</div>
		@endif
		@if(count($productions))
			<div class="tab-pane fade @if(array_key_first($titles) === 'producer') show active @endif" id="producer" role="tabpanel" aria-labelledby="producer-tab">
				{!! ElementsHelper::getElements($request, $productions, 'films', $options) !!}
			</div>
		@endif
		@if(count($roles))
			<div class="tab-pane fade @if(array_key_first($titles) === 'actor') show active @endif" id="actor" role="tabpanel" aria-labelledby="actor-tab">
				{!! ElementsHelper::getElements($request, $roles, 'films', $options) !!}
			</div>
		@endif
	</div>
@stop
