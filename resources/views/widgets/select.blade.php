@php
    $select_name = (isset($select_name)) ? $select_name : '';
    $selected = (isset($selected)) ? $selected : null;
    $options = (isset($options)) ? $options : array();
@endphp
<select name="{!! $select_name !!}" class="custom-select" autocomplete="off">
    @foreach($options as $key => $value)
        <option value="{!! $key !!}" @if($key == $selected) selected="selected" @endif>{!! $value !!}</option>
    @endforeach
</select>
