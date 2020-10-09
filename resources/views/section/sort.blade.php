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
<form class="sort" method="GET">
<div class="input-group input-group-sm">
    @include('widgets.select', array('select_name' => 'sort', 'options' => $sort_options, 'selected' => $sort))
    @include('widgets.select', array('select_name' => 'order', 'options' => $sort_direction, 'selected' => $order))
    <input type="hidden" name="page" value="{{ $page }}" autocomplete="off" />
    <div class="input-group-append">
        <input type="submit" value="Сортировать" class="btn btn-secondary" />
    </div>
</div>
</form>
