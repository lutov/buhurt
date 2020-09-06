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

	<div class="row">

		<div class="col-md-12">

			{!! ElementsHelper::getSectionHeader($request, $options); !!}
			{!! ElementsHelper::getElement($request, $element, $section->alt_name, $options) !!}
			@if($relations->count())
			<?php

				$elements = '';
				foreach($relations as $relation) {
					$relation_section = SectionsHelper::getSectionBy($relation->element_type);
					$relation_element = $relation->element_type::find($relation->element_id);
					$relation_element->caption = $relation->caption;
					$elements .= ElementsHelper::getElement($request, $relation_element, $relation_section, $options);
				}
				echo $elements;

			?>
			@endif
			{!! ElementsHelper::getSectionFooter(); !!}

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

		<div class="row">

			<div class="col-md-10">
		
			{!! Form::open(array('action' => array('Search\RelationsController@addRelation', $section->alt_name, $element->id), 'class' => 'add_relation', 'method' => 'POST', 'files' => true)) !!}

				<div class="form-row">
					<div class="col">
					{!! Form::text('relations', $value = '', $attributes = array(
						'placeholder' => 'Связанные произведения',
						'id' => 'relations',
						'class' => 'form-control'
					)) !!}
					</div>
					<div class="col">
					{!! Form::select('section', $sections_list, $section->alt_name, $attributes = array(
    					'class' => 'form-control',
    					'autocomplete' => 'off',
					)) !!}
					</div>
					<div class="col">
						{!! Form::select('relation', $relations_list, null, $attributes = array(
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

		<?php

		$relations_edit_form = '';
		foreach($relations_simple as $relation_simple) {

			$relation_section = SectionsHelper::getSectionBy($relation_simple['element_type']);

			$relations_edit_form .= '<div class="row mt-3">';
			$relations_edit_form .= '<div class="col-md-10">';

			$relations_edit_form .= Form::open(array(
					'action' => array('Search\RelationsController@editRelation', $section->alt_name, $element->id),
					'class' => 'edit_relation',
					'method' =>'POST',
					'files' => true
			));

			$relations_edit_form .= '<div class="form-row">';

			$relations_edit_form .= '<input type="hidden" name="relation_id" value="'.$relation_simple['id'].'">';

			$relations_edit_form .= '<div class="col">';
			$relations_edit_form .= Form::text('element_id', $value = $relation_simple['element_id'], $attributes = array(
					'placeholder' => 'Произведение',
					'id' => 'element_id_'.$relation_simple['element_id'],
					'class' => 'form-control',
					'onmouseover' => 'preview('.$relation_simple['element_id'].', \''.$relation_section.'\')',
					'autocomplete' => 'off',
			));
			$relations_edit_form .= '</div>';

			$relations_edit_form .= '<div class="col">';
			$relations_edit_form .= Form::select('element_section', $sections_list, $relation_section, $attributes = array(
					'class' => 'form-control',
					'autocomplete' => 'off',
			));
			$relations_edit_form .= '</div>';

			$relations_edit_form .= '<div class="col">';
			$relations_edit_form .= Form::select('relation_type', $relations_list, $relation_simple['relation_id'], $attributes = array(
					'class' => 'form-control',
					'autocomplete' => 'off',
			));

			$relations_edit_form .= '</div>';

			$relations_edit_form .= '<div class="col">';
			$relations_edit_form .= Form::submit('Сохранить', $attributes = array(
					'id' => 'relation_save',
					'class' => 'btn btn-secondary',
					'role' => 'button'
			));
			$relations_edit_form .= '</div>';

			$relations_edit_form .= '</div>';

			$relations_edit_form .= Form::close();

			$relations_edit_form .= '</div>';

			$relations_edit_form .= '<div class="col-md-2">';
			$relations_edit_form .= Form::open(array(
					'action' => array('Search\RelationsController@deleteRelation', $section->alt_name, $element->id),
					'class' => 'delete_relation',
					'method' =>'POST',
					'files' => true
			));
			$relations_edit_form .= '<input type="hidden" name="relation_id" value="'.$relation_simple['id'].'">';
			$relations_edit_form .= Form::submit('Удалить', $attributes = array(
					'id' => 'relation_save',
					'class' => 'btn btn-danger',
					'role' => 'button'
			));
			$relations_edit_form .= Form::close();
			$relations_edit_form .= '</div>';

			$relations_edit_form .= '</div>';

		}
		echo $relations_edit_form;

		?>
				
	@endif

@stop