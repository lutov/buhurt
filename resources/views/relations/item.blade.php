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

					//echo DebugHelper::dump($rel_elem->element_type, true); die();

					$relation_type = $rel_elem->element_type;
					$relation_section = SectionsHelper::getSectionBy($relation_type);

					//echo DebugHelper::dump($relation_type, true); die();

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
		
			{!! Form::open(array('action' => array('RelationsController@addRelation', $section, $element->id), 'class' => 'add_relation', 'method' => 'POST', 'files' => true)) !!}

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

		<?php

			//echo DebugHelper::dump($relations_simple, true);

			$relations_edit_form = '';
			foreach($relations_simple as $relation_simple) {

				$relation_section = SectionsHelper::getSectionBy($relation_simple['element_type']);

				$relations_edit_form .= '<div class="row mt-3">';
				$relations_edit_form .= '<div class="col-md-10">';

				$relations_edit_form .= Form::open(array(
					'action' => array('RelationsController@editRelation', $section, $element->id),
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
						$relations_edit_form .= Form::select('element_section', $section_list, $relation_section, $attributes = array(
							'class' => 'form-control',
							'autocomplete' => 'off',
						));
						$relations_edit_form .= '</div>';

						$relations_edit_form .= '<div class="col">';
						$relations_edit_form .= Form::select('relation_type', $relation_list, $relation_simple['relation_id'], $attributes = array(
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
					'action' => array('RelationsController@deleteRelation', $section, $element->id),
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