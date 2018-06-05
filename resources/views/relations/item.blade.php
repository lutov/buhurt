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

				//echo DebugHelper::dump($relations, true);

				foreach($relations as $rel_elem) {

					//echo DebugHelper::dump($rel_elem->element_type, true);

					$relation_type = $rel_elem->element_type;
					$relation_section = SectionsHelper::getSectionBy($relation_type);

					$relation = $rel_elem->$relation_section[0];

					$options['add_text'] = $rel_elem->relation->name;

					echo ElementsHelper::getElement($request, $relation, $relation_section, $options);

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

				<div class="form-row">
					<div class="col">
					{!! Form::text('relations', $value = '', $attributes = array(
						'placeholder' => 'Cвязанные произведения',
						'id' => 'relations',
						'class' => 'form-control'
					)) !!}
					</div>
					<div class="col">
					{!! Form::select('section', $section_list, $section, $attributes = array(
    					'class' => 'form-control',
    					'autocomplete' => 'off',
					)) !!}
					</div>
					<div class="col">
						{!! Form::select('relation', $relation_list, null, $attributes = array(
    						'class' => 'form-control'
						)) !!}
					</div>
					<div class="col">
						{!! Form::submit('Сохранить', $attributes = array('id' => 'relation_save', 'class' => 'btn btn-secondary', 'role' => 'button')) !!}
					</div>
				</div>

			{!! Form::close() !!}

			</div>

		</div>
				
	@endif

@stop