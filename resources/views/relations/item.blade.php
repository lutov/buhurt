@extends('layouts.default')

@section('title')Связи «{!! $element->name !!}»@stop

@section('subtitle')@stop

@section('keywords'){!! $element->name !!}, сиквелы, приквелы, ремейки, адаптации@stop
@section('description')Связи «{!! $element->name !!}» с другими произведениями@stop

@section('content')

	<section class="text-center">
		<h1 class="mt-5">@yield('title')</h1>
		<h2 class="mb-3">@yield('subtitle')</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">

			{!! ElementsHelper::getHeader($request, $options); !!}
			{!! ElementsHelper::getElement($request, $element, $section->alt_name, $options) !!}
			@if($element->relations && $element->relations->count())
			<?php

				dd($element->relations);
				$elements = '';
				foreach($element->relations as $relation) {
					$relation_section = SectionsHelper::getSectionBy(class_basename($element));
					//dd($relation_section);
					$elements .= ElementsHelper::getElement($request, $relation, $relation_section, $options);
				}
				echo $elements;

			?>
			@endif
			{!! ElementsHelper::getFooter(); !!}

		</div>

	</div>
	
	@if(RolesHelper::isAdmin($request))

		<script>

			function preview(element_id, section) {
			    var block = $('#element_id_'+element_id);
			    if('' === block.prop('title')) {
                    var path = '/api/' + section + '/' + element_id + '/';
                    $.get(path, {}, function (data) {
                        block.prop('title', data.name);
                    });
                }
			}

		</script>

		<div class="row mt-5">

			<div class="col-md-10">
		
			{!! Form::open(array('action' => array('Search\RelationsController@addRelation', $section, $element->id), 'class' => 'add_relation', 'method' => 'POST', 'files' => true)) !!}

				<div class="form-row">
					<div class="col">
					{!! Form::text('relations', $value = '', $attributes = array(
						'placeholder' => 'Связанные произведения',
						'id' => 'relations',
						'class' => 'form-control'
					)) !!}
					</div>
					<div class="col">
					{!! Form::select('section', $sections, $section, $attributes = array(
    					'class' => 'form-control',
    					'autocomplete' => 'off',
					)) !!}
					</div>
					<div class="col">
						{!! Form::select('relation', $relations, null, $attributes = array(
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