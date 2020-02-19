<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 19.02.2020
 * Time: 16:36
 */

namespace App\Helpers;


class RelationsHelper {

	public static function editRelationsForm() {

		$relations_edit_form = '';
		foreach($relations_simple as $relation_simple) {

			$relation_section = SectionsHelper::getSectionBy($relation_simple['element_type']);

			$relations_edit_form .= '<div class="row mt-3">';
			$relations_edit_form .= '<div class="col-md-10">';

			$relations_edit_form .= Form::open(array(
				'action' => array('Search\RelationsController@editRelation', $section, $element->id),
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
				'action' => array('Search\RelationsController@deleteRelation', $section, $element->id),
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

	}

}