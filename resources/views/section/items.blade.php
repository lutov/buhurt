@php
    if(!isset($grid)) {$grid = 'quarter';}
@endphp
@foreach ($elements as $element)
    <div class="@include('card.grid.'.$grid)">
        @include('section.cards.item')
    </div>
@endforeach