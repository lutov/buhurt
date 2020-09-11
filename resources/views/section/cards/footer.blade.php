@if(isset($options['paginate']) && $options['paginate'])
    @if(!empty($request->get('sort')))
        {!! $elements->appends(array('sort' => $options['sort'], 'order' => $options['order'],))->render() !!}
    @else
        {!! $elements->render() !!}
    @endif
@endif
