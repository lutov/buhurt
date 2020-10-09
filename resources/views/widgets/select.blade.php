@php
    $select_name = (isset($select_name)) ? $select_name : '';
    $select_class = (isset($select_class)) ? $select_class : 'custom-select';
    $selected = (isset($selected)) ? $selected : null;
    $options = (isset($options)) ? $options : array();
@endphp
<select name="{!! $select_name !!}" class="{!! $select_class !!}" autocomplete="off">
    @foreach($options as $key => $value)
        <option value="{!! $key !!}" @if($key == $selected) selected="selected" @endif>{!! $value !!}</option>
    @endforeach
</select>
