<div class="card @include('card.class') mb-4 text-center">
    <div class="card-header">
        <h1 class="card-title m-0">{!! $section->name; !!}</h1>
        @if(isset($subtitle))
            <div class="h2 card-subtitle text-muted mt-1">{!! $subtitle !!}</div>
        @endif
    </div>
    @if(!empty($options['sort_options']))
        <div class="card-body">
            <noindex><!--noindex-->@include('section.sort')<!--/noindex--></noindex>
        </div>
    @endif
    @if(isset($export))
        <div class="card-footer small">
            <a href="{!! $export !!}">Экспортировать в файл</a>
        </div>
    @endif
</div>
