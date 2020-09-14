<div class="pb-4">
    <div class="card @include('card.class')">
        <div class="card-body">
            <div class="list-group-flush card-columns">
                @foreach($elements as $element)
                    @if('' != $element->name)
                        @php
                            /** @var $element */
                            $url = '/';
                            if(!empty($section)) {$url .= $section->alt_name.'/';}
                            if(isset($options['subsection'])) {$url .= $options['subsection'].'/';}
                            $url .= $element->id;
                            if(isset($options['anchor'])) {$url .= '#'.$options['anchor'];}
                        @endphp
                        <a href="{{$url}}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-light">
                            <span class="text-dark">{!! $element->name !!}</span>
                            @if($options['count']) <span class="small text-secondary">({{$element->count}})</span> @endif
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@include('section.cards.footer')
