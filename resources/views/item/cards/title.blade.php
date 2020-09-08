<div class="card @include('card.class') text-center mb-4">
    <div class="card-body">
        <h1 class="card-title">{!! $title !!}</h1>
        @if(isset($subtitle))
            <div class="h2 card-subtitle text-muted">{!! $subtitle !!}</div>
        @endif
    </div>
</div>
