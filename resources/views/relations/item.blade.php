@extends('layouts.default')

@section('title')
	Связи «{!! $element->name !!}»
@stop

@section('subtitle')

@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

	<div id="mainpage">
	
	{!! Helpers::get_element($element, $section) !!}
	
    @if(!empty($relations))
	<?php
			
		//echo '<pre>'.print_r($relation, true).'</pre>';
		//echo '<pre>'.print_r($element, true).'</pre>';
		//echo '<pre>'.print_r($relations[0]->relation->name, true).'</pre>';
		// Input::get('book_name', '')
				
		foreach($relations as $rel_elem) {
					
			//echo '<pre>'.print_r($rel_elem->films[0]->name, true).'</pre>';
					
			//echo '<p>';
			//echo $rel_elem->relation->name;
			//echo '<br>';
			//echo $rel_elem->films[0]->name;
			//echo '</p>';
					
			echo Helpers::get_element($rel_elem->$section[0], $section, $rel_elem->relation->name.': ');
					
		}
	?>
    @endif
	
		<div class="element_card">
			<div class="element_description"></div>
        </div>		
		
    </div>
	
	@if(Helpers::is_admin())
		
		{!! Form::open(array('action' => array('RelationsController@add_relation', $section, $element->id), 'class' => 'add_relation', 'method' => 'POST', 'files' => true)) !!}
			<p>{!! Form::text('relations', $value = '', $attributes = array('placeholder' => 'Cвязанные произведения', 'id' => 'relations', 'class' => 'half')) !!}</p>
			<p>
			{!! Form::select('relation', $relation) !!}
			{!! Form::submit('Сохранить', $attributes = array('id' => 'relation_save')) !!}
			</p>
		{!! Form::close() !!}
				
	@endif

@stop