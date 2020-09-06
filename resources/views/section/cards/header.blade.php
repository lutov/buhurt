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
<div class="card @include('card.class') mb-4 text-center">
    <div class="card-header">
        <h1 class="card-title m-0">{!! $section->name; !!}</h1>
    </div>
    @if(!empty($sort_options))
        <div class="card-body">
            <noindex><!--noindex-->@include('section.sort')<!--/noindex--></noindex>
        </div>
    @endif
</div>
