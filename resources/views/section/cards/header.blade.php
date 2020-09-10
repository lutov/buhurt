<div class="card @include('card.class') mb-4 text-center">
    <div class="card-header">
        <h1 class="card-title m-0">{!! $section->name; !!}</h1>
    </div>
    @if(!empty($options['sort_options']))
        <div class="card-body">
            <noindex><!--noindex-->@include('section.sort')<!--/noindex--></noindex>
        </div>
    @endif
</div>
