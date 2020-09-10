@php
    /** @var $request */
    $page = $request->get('page', 1);
    $sort_options = $options['sort_options'];
    $sort = $options['sort'];
    $order = $options['order'];
    $sort_direction = array(
        'asc' => 'А→Я',
        'desc' => 'Я→А'
    );
@endphp
{!! Form::open(array('class' => 'sort', 'method' => 'GET')); !!}
<div class="input-group input-group-sm">
    {!! Form::select('sort', $sort_options, $sort, array('class' => 'custom-select', 'autocomplete' => 'off')); !!}
    {!! Form::select('order', $sort_direction, $order, array('class' => 'custom-select', 'autocomplete' => 'off')); !!}
    {!! Form::hidden('page', $page); !!}
    <div class="input-group-append">
        {!! Form::submit('Сортировать', array('class' => 'btn btn-secondary')); !!}
    </div>
</div>
{!! Form::close(); !!}
