@extends('layouts.default')

@section('title')Связи «{!! $element->name !!}»@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="mt-5">@yield('title')</h1>
		<h2 class="mb-3">@yield('subtitle')</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">

			<?php
			$options = array(
				'header' => false,
				'paginate' => false,
				'footer' => false,
			);
			?>

			{!! ElementsHelper::getHeader(); !!}
	
			{!! ElementsHelper::getElement($request, $element, $section, $options) !!}

			@if(!empty($relations))
			<?php


				foreach($relations as $rel_elem) {

					$options['add_text'] = $rel_elem->relation->name;

					echo ElementsHelper::getElement($request, $rel_elem->$section[0], $section, $options);

				}
			?>
			@endif

			{!! ElementsHelper::getFooter(); !!}

		</div>

	</div>
	
	@if(RolesHelper::isAdmin($request))

		<div class="row mt-5">

			<div class="col-md-12">
		
			{!! Form::open(array('action' => array('RelationsController@add_relation', $section, $element->id), 'class' => 'add_relation', 'method' => 'POST', 'files' => true)) !!}

				<p>{!! Form::text('relations', $value = '', $attributes = array(
					'placeholder' => 'Cвязанные произведения',
					'id' => 'relations',
					'class' => 'form-control w-100'
				)) !!}</p>

				<p>
				{!! Form::select('relation', $relation, null, $attributes = array(
					'class' => 'form-control w-25'
				)) !!}
				</p>

				<p>
				{!! Form::submit('Сохранить', $attributes = array('id' => 'relation_save', 'class' => 'btn btn-secondary', 'role' => 'button')) !!}
				</p>

			{!! Form::close() !!}

			</div>

		</div>
				
	@endif

@stop