@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle')@stop

@section('keywords')группа, {!! $element->name !!}, альбомы@stop
@section('description')Группа {!! $element->name !!}@stop

@section('content')

	<section class="text-center mt-5 mb-3">
		<h1 class="">@yield('title')</h1>
		<h2 class="">@yield('subtitle')</h2>
	</section>

	<div itemscope itemtype="http://schema.org/MusicGroup" class="mt-5">

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

		@if(count($albums))
			<div class="tab-pane fade @if(array_key_first($titles) === 'albums') show active @endif" id="albums" role="tabpanel" aria-labelledby="albums-tab">
				<div class="row mt-5">
					<div class="col-md-12">
						{!! ElementsHelper::getElements($request, $albums, 'albums', $options) !!}
					</div>
				</div>
			</div>
		@endif

	</div>

@stop